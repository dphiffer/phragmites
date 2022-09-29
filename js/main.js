class ScrollyImageBlock {

	constructor(el) {
		this.el = el;
		this.setup();
	}

	setup() {
		let scroller = scrollama();
		let sticky = this.el.querySelector('.sticky-image__inner');
		sticky.style.height = this.el.querySelector('.image-0').offsetHeight + 'px';
		scroller.setup({
			step: '.scrolly-image-block .caption'
		}).onStepEnter((response) => {
			let image = this.el.querySelector(`.image-${response.index}`);
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
}

class VideosBlock {

	constructor(el) {
		this.el = el;
		this.setup();
	}

	setup() {
		let links = this.el.querySelectorAll('.video-list a');
		for (let link of links) {
			link.addEventListener('click', this.selectVideo.bind(this));
		}
	}

	selectVideo(e) {
		e.preventDefault();
		let item = e.target.closest('li');
		if (!item) {
			return;
		}
		if (item.classList.contains('selected')) {
			return;
		}
		let link = item.querySelector('a');
		let selected = this.el.querySelector('.selected');
		selected.classList.remove('selected');
		item.classList.add('selected');
		let href = link.getAttribute('href');
		fetch(href, {
			headers: {
				'Accept': 'application/json'
			}
		}).then(rsp => rsp.json()).then(rsp => {
			if (rsp.html) {
				this.el.querySelector('.content').innerHTML = rsp.html;
			}
		});
	}
}

window.addEventListener('DOMContentLoaded', () => {
	let controllers = {
		'.scrolly-image-block': ScrollyImageBlock,
		'.videos-block': VideosBlock
	};
	for (let querySelector in controllers) {
		let elements = document.querySelectorAll(querySelector);
		let controller = controllers[querySelector];
		for (let el of elements) {
			new controller(el);
		}
	}
});
