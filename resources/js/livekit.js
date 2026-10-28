import { Room, RoomEvent, Track } from 'livekit-client';

export class LiveKitStudio {
    constructor(stage) {
        this.stage = stage;
        this.tokenUrl = stage.dataset.tokenUrl;
        this.isHost = stage.dataset.isHost === 'true';
        this.isLive = stage.dataset.isLive === 'true';
        this.broadcastType = stage.dataset.broadcastType || 'video';

        this.grid = stage.querySelector('#livekit-media-grid');
        this.placeholder = stage.querySelector('#livekit-placeholder');
        this.statusIndicator = stage.querySelector('#lk-status-indicator');
        this.participantsCount = stage.querySelector('#lk-participants-count');

        this.joinBtn = stage.querySelector('#lk-join-btn');
        this.micBtn = stage.querySelector('#lk-mic-btn');
        this.camBtn = stage.querySelector('#lk-cam-btn');
        this.screenBtn = stage.querySelector('#lk-screen-btn');
        this.leaveBtn = stage.querySelector('#lk-leave-btn');

        this.room = null;
        this.isMicOn = false;
        this.isCamOn = false;
        this.isScreenOn = false;

        this.init();
    }

    setStatus(text) {
        if (this.statusIndicator) {
            this.statusIndicator.textContent = text;
        }
    }

    updateParticipants() {
        if (!this.participantsCount || !this.room) {
            return;
        }

        const count = this.room.numParticipants + 1;
        this.participantsCount.textContent = `${count} in room`;
    }

    async fetchCredentials() {
        const response = await fetch(this.tokenUrl, {
            headers: { Accept: 'application/json' },
        });

        if (!response.ok) {
            throw new Error(`Failed to get LiveKit token: HTTP ${response.status}`);
        }

        return response.json();
    }

    async init() {
        if (this.isHost) {
            this.bindHostControls();
            if (this.isLive && this.joinBtn) {
                // Auto connect host if live
                this.connect();
            }
        } else if (this.isLive) {
            // Viewer auto-connects to live broadcast
            this.connect();
        }
    }

    bindHostControls() {
        this.joinBtn?.addEventListener('click', () => this.connect());
        this.leaveBtn?.addEventListener('click', () => this.disconnect());

        this.micBtn?.addEventListener('click', async () => {
            if (!this.room) {
                return;
            }
            try {
                this.isMicOn = !this.isMicOn;
                await this.room.localParticipant.setMicrophoneEnabled(this.isMicOn);
                this.micBtn.textContent = this.isMicOn ? 'Mute Mic' : 'Unmute Mic';
                this.micBtn.classList.toggle('button-active', this.isMicOn);
            } catch (err) {
                console.error('Failed to toggle microphone', err);
                this.setStatus('Microphone access denied');
            }
        });

        this.camBtn?.addEventListener('click', async () => {
            if (!this.room) {
                return;
            }
            try {
                this.isCamOn = !this.isCamOn;
                await this.room.localParticipant.setCameraEnabled(this.isCamOn);
                this.camBtn.textContent = this.isCamOn ? 'Turn Camera Off' : 'Turn Camera On';
                this.camBtn.classList.toggle('button-active', this.isCamOn);
            } catch (err) {
                console.error('Failed to toggle camera', err);
                this.setStatus('Camera access denied');
            }
        });

        this.screenBtn?.addEventListener('click', async () => {
            if (!this.room) {
                return;
            }
            try {
                this.isScreenOn = !this.isScreenOn;
                await this.room.localParticipant.setScreenShareEnabled(this.isScreenOn);
                this.screenBtn.textContent = this.isScreenOn ? 'Stop Screen' : 'Share Screen';
                this.screenBtn.classList.toggle('button-active', this.isScreenOn);
            } catch (err) {
                console.error('Failed to toggle screen share', err);
                this.setStatus('Screen share cancelled');
            }
        });
    }

    async connect() {
        this.setStatus('Connecting to LiveKit...');
        if (this.joinBtn) {
            this.joinBtn.disabled = true;
        }

        try {
            const data = await this.fetchCredentials();
            const { serverUrl, token } = data;

            this.room = new Room({
                adaptiveStream: true,
                dynacast: true,
            });

            this.setupRoomEvents();

            await this.room.connect(serverUrl, token);
            this.setStatus(this.isHost ? 'LiveKit studio connected' : 'Connected (Live)');
            this.updateParticipants();

            if (this.placeholder) {
                this.placeholder.style.display = 'none';
            }
            if (this.grid) {
                this.grid.style.display = 'flex';
            }

            if (this.isHost) {
                if (this.joinBtn) {
                    this.joinBtn.style.display = 'none';
                }
                if (this.leaveBtn) {
                    this.leaveBtn.style.display = 'inline-flex';
                }
                if (this.micBtn) {
                    this.micBtn.disabled = false;
                }
                if (this.camBtn) {
                    this.camBtn.disabled = false;
                }
                if (this.screenBtn) {
                    this.screenBtn.disabled = false;
                }

                // Automatically enable mic by default for host
                try {
                    await this.room.localParticipant.setMicrophoneEnabled(true);
                    this.isMicOn = true;
                    if (this.micBtn) {
                        this.micBtn.textContent = 'Mute Mic';
                        this.micBtn.classList.add('button-active');
                    }
                } catch {
                    // Host may not have granted permission yet
                }

                if (this.broadcastType === 'video') {
                    try {
                        await this.room.localParticipant.setCameraEnabled(true);
                        this.isCamOn = true;
                        if (this.camBtn) {
                            this.camBtn.textContent = 'Turn Camera Off';
                            this.camBtn.classList.add('button-active');
                        }
                    } catch {
                        // Camera permission optional or postponed
                    }
                }

                // Attach local preview track
                this.renderLocalTracks();
            }
        } catch (error) {
            console.error('LiveKit connection error:', error);
            this.setStatus('LiveKit server unreachable');
            if (this.placeholder) {
                this.placeholder.style.display = 'block';
                this.placeholder.innerHTML = `<p>Could not connect to LiveKit server. Ensure the server is running at the configured URL.</p>`;
            }
            if (this.joinBtn) {
                this.joinBtn.disabled = false;
            }
        }
    }

    renderLocalTracks() {
        if (!this.grid || !this.room) {
            return;
        }

        const existingLocal = this.grid.querySelector('.livekit-local-track');
        if (existingLocal) {
            existingLocal.remove();
        }

        this.room.localParticipant.trackPublications.forEach((publication) => {
            if (publication.track && publication.track.kind === Track.Kind.Video) {
                const videoEl = publication.track.attach();
                videoEl.classList.add('livekit-video', 'livekit-local-track');
                videoEl.muted = true;
                this.grid.append(videoEl);
            }
        });
    }

    setupRoomEvents() {
        this.room.on(RoomEvent.TrackSubscribed, (track, publication, participant) => {
            if (this.placeholder) {
                this.placeholder.style.display = 'none';
            }

            const element = track.attach();
            element.dataset.participantId = participant.identity;
            element.dataset.trackId = track.sid;

            if (track.kind === Track.Kind.Video) {
                element.classList.add('livekit-video');
            } else if (track.kind === Track.Kind.Audio) {
                element.classList.add('livekit-audio');
            }

            this.grid.append(element);
            this.setStatus(`Live (${participant.name || 'Host'})`);
        });

        this.room.on(RoomEvent.TrackUnsubscribed, (track) => {
            track.detach().forEach((el) => el.remove());
        });

        this.room.on(RoomEvent.LocalTrackPublished, () => {
            this.renderLocalTracks();
        });

        this.room.on(RoomEvent.LocalTrackUnpublished, (publication) => {
            if (publication.track) {
                publication.track.detach().forEach((el) => el.remove());
            }
        });

        this.room.on(RoomEvent.ParticipantConnected, () => this.updateParticipants());
        this.room.on(RoomEvent.ParticipantDisconnected, () => this.updateParticipants());

        this.room.on(RoomEvent.Disconnected, () => {
            this.setStatus('Disconnected');
            if (this.grid) {
                this.grid.querySelectorAll('.livekit-video, .livekit-audio').forEach((el) => el.remove());
            }
            if (this.placeholder) {
                this.placeholder.style.display = 'block';
                this.placeholder.innerHTML = '<p>Disconnected from room.</p>';
            }
        });
    }

    disconnect() {
        if (this.room) {
            this.room.disconnect();
            this.room = null;
        }

        if (this.joinBtn) {
            this.joinBtn.style.display = 'inline-flex';
            this.joinBtn.disabled = false;
        }
        if (this.leaveBtn) {
            this.leaveBtn.style.display = 'none';
        }
        if (this.micBtn) {
            this.micBtn.disabled = true;
            this.micBtn.classList.remove('button-active');
            this.micBtn.textContent = 'Unmute Mic';
        }
        if (this.camBtn) {
            this.camBtn.disabled = true;
            this.camBtn.classList.remove('button-active');
            this.camBtn.textContent = 'Turn Camera On';
        }
        if (this.screenBtn) {
            this.screenBtn.disabled = true;
            this.screenBtn.classList.remove('button-active');
            this.screenBtn.textContent = 'Share Screen';
        }

        this.setStatus('Disconnected');
    }
}

export const initLiveKit = () => {
    document.querySelectorAll('[data-livekit]').forEach((el) => new LiveKitStudio(el));
};
