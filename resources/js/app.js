import './bootstrap';

import 'bootstrap'; // ✅ This pulls in bootstrap.js + Popper

document.addEventListener('DOMContentLoaded', function() {
    const wrapper = document.querySelector('.app-main');
    if(wrapper) {
        wrapper.style.opacity = 0;
        setTimeout(() => {
            wrapper.style.opacity = 1;
        }, 50);
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const fullscreenBtn = document.querySelector('[data-lte-toggle="fullscreen"]');
    if (fullscreenBtn) {
        fullscreenBtn.addEventListener('click', function (e) {
            e.preventDefault();
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen();
                fullscreenBtn.querySelector('[data-lte-icon="maximize"]').style.display = 'none';
                fullscreenBtn.querySelector('[data-lte-icon="minimize"]').style.display = '';
            } else {
                document.exitFullscreen();
                fullscreenBtn.querySelector('[data-lte-icon="maximize"]').style.display = '';
                fullscreenBtn.querySelector('[data-lte-icon="minimize"]').style.display = 'none';
            }
        });

        document.addEventListener('fullscreenchange', function () {
            if (!document.fullscreenElement) {
                fullscreenBtn.querySelector('[data-lte-icon="maximize"]').style.display = '';
                fullscreenBtn.querySelector('[data-lte-icon="minimize"]').style.display = 'none';
            }
        });
    }
});
