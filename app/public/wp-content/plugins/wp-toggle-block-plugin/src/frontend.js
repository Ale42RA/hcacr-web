document.addEventListener('DOMContentLoaded', function () {
    const toggleBlocks = document.querySelectorAll('.toggle-block-container');
    
    toggleBlocks.forEach(function (toggleBlock) {
        const button = toggleBlock.querySelector('.toggle-button');
        const firstBlock = toggleBlock.querySelectorAll('.block-show')[0];
        const secondBlock = toggleBlock.querySelectorAll('.block-show')[1];
        
        button.addEventListener('click', function () {
            if (firstBlock.classList.contains('block-hide')) {
                firstBlock.classList.remove('block-hide');
                secondBlock.classList.add('block-hide');
                button.textContent = 'Switch to Shortcode Block';
            } else {
                firstBlock.classList.add('block-hide');
                secondBlock.classList.remove('block-hide');
                button.textContent = 'Switch to Paragraph Block';
            }
        });
    });
});