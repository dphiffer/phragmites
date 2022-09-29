window.addEventListener('DOMContentLoaded', () => {
	let scrollyImage = document.querySelector('.scrolly-image-block');
	if (scrollyImage) {
		let scroller = scrollama();
		let sticky = scrollyImage.querySelector('.sticky-image__inner');
		sticky.style.height = scrollyImage.querySelector('.image-0').offsetHeight + 'px';
		scroller.setup({
			step: '.scrolly-image-block .caption'
		}).onStepEnter((response) => {
			let image = scrollyImage.querySelector(`.image-${response.index}`);
			if (image) {
				image.classList.add('is-visible');
			}
		}).onStepExit((response) => {
			let image = scrollyImage.querySelector(`.image-${response.index}`);
			if (image) {
				image.classList.remove('is-visible');
			}
		});
	}
});
