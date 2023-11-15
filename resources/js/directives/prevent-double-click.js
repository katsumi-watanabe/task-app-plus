export default {
    mounted(el) {
        el.addEventListener('click', () => {
            if (!el.disabled) {
                el.disabled = true;
                setTimeout(() => {
                    el.disabled = false;
                }, 1000);
            }
        });
    }
};
