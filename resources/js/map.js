const TILE_SIZE = 256;
const MIN_ZOOM = 2;
const MAX_ZOOM = 18;
const DEFAULT_TILE_URL = 'https://tile.openstreetmap.org/{z}/{x}/{y}.png';
const SVG_NS = 'http://www.w3.org/2000/svg';

const project = ([lat, lng], zoom) => {
    const scale = TILE_SIZE * 2 ** zoom;
    const sin = Math.min(Math.max(Math.sin((lat * Math.PI) / 180), -0.9999), 0.9999);

    return [
        ((lng + 180) / 360) * scale,
        (0.5 - Math.log((1 + sin) / (1 - sin)) / (4 * Math.PI)) * scale,
    ];
};

const unproject = ([x, y], zoom) => {
    const scale = TILE_SIZE * 2 ** zoom;
    const n = Math.PI - (2 * Math.PI * y) / scale;

    return [
        (180 / Math.PI) * Math.atan(0.5 * (Math.exp(n) - Math.exp(-n))),
        (x / scale) * 360 - 180,
    ];
};

const svg = (name, attributes = {}) => {
    const node = document.createElementNS(SVG_NS, name);
    Object.entries(attributes).forEach(([key, value]) => node.setAttribute(key, value));

    return node;
};

export class TileMap {
    constructor(element, { tileUrl, center = [20, 0], zoom = 3, onClick = null } = {}) {
        this.element = element;
        this.tileUrl = tileUrl || DEFAULT_TILE_URL;
        this.center = center;
        this.zoom = zoom;
        this.onClick = onClick;
        this.points = [];
        this.tiles = new Map();
        this.drag = null;
        this.lastWheel = 0;

        this.build();
        this.bind();
        this.render();
    }

    build() {
        this.element.replaceChildren();
        this.element.classList.add('map');

        this.tileLayer = document.createElement('div');
        this.tileLayer.className = 'map-tiles';

        this.overlay = svg('svg', { class: 'map-overlay', 'aria-hidden': 'true' });

        const controls = document.createElement('div');
        controls.className = 'map-controls';
        controls.append(this.controlButton('+', 'Zoom in', 1), this.controlButton('\u2212', 'Zoom out', -1));

        const attribution = document.createElement('a');
        attribution.className = 'map-attribution';
        attribution.href = 'https://www.openstreetmap.org/copyright';
        attribution.rel = 'noopener noreferrer';
        attribution.target = '_blank';
        attribution.textContent = '\u00a9 OpenStreetMap contributors';

        this.element.append(this.tileLayer, this.overlay, controls, attribution);
    }

    controlButton(label, title, delta) {
        const button = document.createElement('button');
        button.type = 'button';
        button.textContent = label;
        button.title = title;
        button.setAttribute('aria-label', title);
        button.addEventListener('click', () => this.zoomAt(delta, this.width / 2, this.height / 2));

        return button;
    }

    bind() {
        this.element.addEventListener('pointerdown', (event) => {
            if (event.target.closest('.map-controls, .map-attribution')) {
                return;
            }

            this.drag = { x: event.clientX, y: event.clientY, moved: false };
            this.element.setPointerCapture(event.pointerId);
            this.element.classList.add('map-dragging');
        });

        this.element.addEventListener('pointermove', (event) => {
            if (!this.drag) {
                return;
            }

            const dx = event.clientX - this.drag.x;
            const dy = event.clientY - this.drag.y;

            if (Math.abs(dx) + Math.abs(dy) > 3) {
                this.drag.moved = true;
            }

            this.drag.x = event.clientX;
            this.drag.y = event.clientY;
            this.panBy(-dx, -dy);
        });

        const finish = (event) => {
            if (!this.drag) {
                return;
            }

            const { moved } = this.drag;
            this.drag = null;
            this.element.classList.remove('map-dragging');

            if (!moved && this.onClick && event.type === 'pointerup') {
                const rect = this.element.getBoundingClientRect();
                this.onClick(this.pixelToLatLng(event.clientX - rect.left, event.clientY - rect.top));
            }
        };

        this.element.addEventListener('pointerup', finish);
        this.element.addEventListener('pointercancel', finish);

        this.element.addEventListener('wheel', (event) => {
            event.preventDefault();

            const now = Date.now();
            if (now - this.lastWheel < 200) {
                return;
            }

            this.lastWheel = now;
            const rect = this.element.getBoundingClientRect();
            this.zoomAt(event.deltaY < 0 ? 1 : -1, event.clientX - rect.left, event.clientY - rect.top);
        }, { passive: false });

        new ResizeObserver(() => this.render()).observe(this.element);
    }

    get width() {
        return this.element.clientWidth;
    }

    get height() {
        return this.element.clientHeight;
    }

    pixelToLatLng(px, py) {
        const [cx, cy] = project(this.center, this.zoom);

        return unproject([cx - this.width / 2 + px, cy - this.height / 2 + py], this.zoom);
    }

    panBy(dx, dy) {
        const [cx, cy] = project(this.center, this.zoom);
        this.center = unproject([cx + dx, cy + dy], this.zoom);
        this.render();
    }

    zoomAt(delta, px, py) {
        const zoom = Math.min(MAX_ZOOM, Math.max(MIN_ZOOM, this.zoom + delta));

        if (zoom === this.zoom) {
            return;
        }

        const anchor = this.pixelToLatLng(px, py);
        this.zoom = zoom;

        const [ax, ay] = project(anchor, zoom);
        this.center = unproject([ax - (px - this.width / 2), ay - (py - this.height / 2)], zoom);
        this.render();
    }

    setPoints(points) {
        this.points = points;
        this.render();
    }

    fitBounds() {
        if (this.points.length === 0 || !this.width || !this.height) {
            return;
        }

        if (this.points.length === 1) {
            this.center = [this.points[0].lat, this.points[0].lng];
            this.zoom = 13;
            this.render();

            return;
        }

        const lats = this.points.map((point) => point.lat);
        const lngs = this.points.map((point) => point.lng);
        const sw = [Math.min(...lats), Math.min(...lngs)];
        const ne = [Math.max(...lats), Math.max(...lngs)];
        const padding = 70;

        let zoom = MAX_ZOOM;
        for (; zoom > MIN_ZOOM; zoom--) {
            const [x1, y1] = project(sw, zoom);
            const [x2, y2] = project(ne, zoom);

            if (Math.abs(x2 - x1) <= this.width - padding * 2 && Math.abs(y2 - y1) <= this.height - padding * 2) {
                break;
            }
        }

        const [x1, y1] = project(sw, zoom);
        const [x2, y2] = project(ne, zoom);
        this.zoom = zoom;
        this.center = unproject([(x1 + x2) / 2, (y1 + y2) / 2], zoom);
        this.render();
    }

    render() {
        const width = this.width;
        const height = this.height;

        if (!width || !height) {
            return;
        }

        const [cx, cy] = project(this.center, this.zoom);
        const left = cx - width / 2;
        const top = cy - height / 2;
        const tileCount = 2 ** this.zoom;
        const needed = new Set();

        const firstX = Math.floor(left / TILE_SIZE);
        const lastX = Math.floor((left + width) / TILE_SIZE);
        const firstY = Math.max(0, Math.floor(top / TILE_SIZE));
        const lastY = Math.min(tileCount - 1, Math.floor((top + height) / TILE_SIZE));

        for (let y = firstY; y <= lastY; y++) {
            for (let x = firstX; x <= lastX; x++) {
                const key = `${this.zoom}/${x}/${y}`;
                needed.add(key);

                let tile = this.tiles.get(key);
                if (!tile) {
                    const wrappedX = ((x % tileCount) + tileCount) % tileCount;
                    tile = document.createElement('img');
                    tile.alt = '';
                    tile.draggable = false;
                    tile.src = this.tileUrl
                        .replace('{z}', this.zoom)
                        .replace('{x}', wrappedX)
                        .replace('{y}', y);
                    this.tileLayer.append(tile);
                    this.tiles.set(key, tile);
                }

                tile.style.transform = `translate(${x * TILE_SIZE - left}px, ${y * TILE_SIZE - top}px)`;
            }
        }

        for (const [key, tile] of this.tiles) {
            if (!needed.has(key)) {
                tile.remove();
                this.tiles.delete(key);
            }
        }

        this.renderOverlay(left, top, width, height);
    }

    renderOverlay(left, top, width, height) {
        this.overlay.setAttribute('width', width);
        this.overlay.setAttribute('height', height);
        this.overlay.replaceChildren();

        const screen = this.points.map((point) => {
            const [x, y] = project([point.lat, point.lng], this.zoom);

            return [x - left, y - top];
        });

        if (screen.length > 1) {
            const path = screen.map(([x, y]) => `${x},${y}`).join(' ');
            this.overlay.append(
                svg('polyline', { points: path, class: 'map-route-casing' }),
                svg('polyline', { points: path, class: 'map-route' }),
            );
        }

        screen.forEach(([x, y], index) => {
            const isEnd = index === 0 || index === screen.length - 1;
            const group = svg('g', { transform: `translate(${x} ${y})` });
            group.append(
                svg('circle', { r: isEnd ? 12 : 10, class: isEnd ? 'map-pin map-pin-end' : 'map-pin' }),
                Object.assign(svg('text', { class: 'map-pin-label', 'text-anchor': 'middle', dy: '4' }), { textContent: index + 1 }),
            );
            this.overlay.append(group);
        });
    }
}

const readPoints = (value) => {
    try {
        const parsed = JSON.parse(value || '[]');

        return Array.isArray(parsed)
            ? parsed.filter((point) => Number.isFinite(point?.lat) && Number.isFinite(point?.lng))
            : [];
    } catch {
        return [];
    }
};

const setupEditor = (element, map, points) => {
    const input = document.querySelector(element.dataset.input);
    const list = document.querySelector(element.dataset.list);
    const form = element.closest('form');

    const sync = (refreshList = true) => {
        input.value = JSON.stringify(points);
        map.setPoints([...points]);

        if (!refreshList) {
            return;
        }

        list.replaceChildren(...points.map((point, index) => {
            const item = document.createElement('li');
            const label = document.createElement('input');
            label.type = 'text';
            label.maxLength = 80;
            label.value = point.label ?? '';
            label.placeholder = index === 0 ? 'Start' : `Stop ${index + 1}`;
            label.setAttribute('aria-label', `Name for stop ${index + 1}`);
            label.addEventListener('input', () => {
                point.label = label.value;
                sync(false);
            });

            const coords = document.createElement('span');
            coords.textContent = `${point.lat.toFixed(4)}, ${point.lng.toFixed(4)}`;

            item.append(label, coords);

            return item;
        }));
    };

    map.onClick = ([lat, lng]) => {
        if (points.length >= 50) {
            return;
        }

        points.push({ lat: Number(lat.toFixed(6)), lng: Number(lng.toFixed(6)), label: '' });
        sync();
    };

    form.querySelector('[data-map-undo]')?.addEventListener('click', () => {
        points.pop();
        sync();
    });

    form.querySelector('[data-map-clear]')?.addEventListener('click', () => {
        points.length = 0;
        sync();
    });

    sync();
};

export const initMaps = () => {
    document.querySelectorAll('[data-map]').forEach((element) => {
        const editable = element.dataset.editable !== undefined;
        const source = editable ? document.querySelector(element.dataset.input).value : element.dataset.points;
        const points = readPoints(source);
        const map = new TileMap(element, { tileUrl: element.dataset.tileUrl });

        map.setPoints(points);
        map.fitBounds();

        if (editable) {
            setupEditor(element, map, points);
        }
    });
};
