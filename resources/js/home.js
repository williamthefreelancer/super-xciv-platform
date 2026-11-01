(() => {
    const initHomeTabs = () => {
        const tabButtons = document.querySelectorAll('.home-auth-tab');
        const panels = document.querySelectorAll('.home-auth-panel-content');

        tabButtons.forEach((button) => {
            button.addEventListener('click', () => {
                const target = button.dataset.tab;

                tabButtons.forEach((btn) => {
                    const isActive = btn === button;
                    btn.classList.toggle('is-active', isActive);
                    btn.setAttribute('aria-selected', isActive ? 'true' : 'false');
                });

                panels.forEach((panel) => {
                    const isTarget = panel.id === `panel-${target}`;
                    panel.classList.toggle('is-active', isTarget);
                });
            });
        });
    };

    const initAdmissionSteps = () => {
        const form = document.getElementById('admission-form');
        if (!form) {
            return;
        }

        const nextButtons = form.querySelectorAll('[data-next-step]');
        const prevButtons = form.querySelectorAll('[data-prev-step]');
        const steps = form.querySelectorAll('.home-step');
        const stepPanels = form.querySelectorAll('.home-step-panel');

        let currentStep = 1;

        const updateSteps = (step) => {
            steps.forEach((stepEl) => {
                const stepNum = Number(stepEl.dataset.step);
                stepEl.classList.toggle('is-active', stepNum <= step);
            });

            stepPanels.forEach((panel) => {
                const panelStep = Number(panel.dataset.stepPanel);
                const isActive = panelStep === step;
                panel.classList.toggle('is-active', isActive);
                panel.setAttribute('aria-hidden', isActive ? 'false' : 'true');
            });

            currentStep = step;
        };

        nextButtons.forEach((button) => {
            button.addEventListener('click', () => {
                if (currentStep >= stepPanels.length) {
                    return;
                }

                const currentPanel = form.querySelector(`.home-step-panel[data-step-panel="${currentStep}"]`);
                if (currentPanel) {
                    const inputs = currentPanel.querySelectorAll('input, select, textarea');
                    let isValid = true;

                    inputs.forEach((input) => {
                        if (input.hasAttribute('required') && !input.checkValidity()) {
                            input.reportValidity();
                            isValid = false;
                        }
                    });

                    if (!isValid) {
                        return;
                    }
                }

                updateSteps(currentStep + 1);
            });
        });

        prevButtons.forEach((button) => {
            button.addEventListener('click', () => {
                if (currentStep <= 1) {
                    return;
                }
                updateSteps(currentStep - 1);
            });
        });

        form.addEventListener('submit', (event) => {
            const lastPanel = form.querySelector('.home-step-panel[data-step-panel="3"]');
            if (lastPanel) {
                const inputs = lastPanel.querySelectorAll('input, select, textarea');
                let isValid = true;

                inputs.forEach((input) => {
                    if (input.hasAttribute('required') && !input.checkValidity()) {
                        input.reportValidity();
                        isValid = false;
                    }
                });

                if (!isValid) {
                    event.preventDefault();
                    updateSteps(3);
                    return;
                }
            }

            event.preventDefault();
            alert('Application submitted (demo). Connect this form to your backend to process admissions.');
        });
    };

    document.addEventListener('DOMContentLoaded', () => {
        initHomeTabs();
        initAdmissionSteps();
    });
})();
