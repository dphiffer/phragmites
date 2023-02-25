class ScrollyImageBlock {

	constructor(el) {
		console.log(el);
		this.el = el;
		this.setup();
	}

	setup() {
		window.addEventListener('resize', this.resize.bind(this));
		this.resize();
		this.count = this.el.querySelectorAll('.image').length;
		let scroller = scrollama();
		scroller.setup({
			parent: this.el,
			step: '.scrolly-image-block .caption'
		}).onStepEnter((response) => {
			let image = this.el.querySelector(`.image-${response.index}`);
			if (image) {
				image.classList.add('is-visible');
			}
		}).onStepExit((response) => {
			if (response.direction == 'down' &&
			    response.index == this.count - 1) {
				return;
			}
			let image = this.el.querySelector(`.image-${response.index}`);
			if (image) {
				image.classList.remove('is-visible');
			}
		});
	}

	resize() {
		let screenWidth = document.documentElement.clientWidth;
		if (this.el.querySelector('.image').offsetWidth > screenWidth) {
			this.el.classList.add('constrain-width');
		} else {
			this.el.classList.remove('constrain-width');
		}
	}
}

class VideosBlock {

	constructor(el) {
		this.el = el;
		this.setup();
	}

	setup() {
		this.checkWidth();
		window.addEventListener('resize', this.checkWidth.bind(this));
		let list = this.el.querySelector('.video-list');
		list.addEventListener('click', this.selectVideo.bind(this));
	}

	checkWidth() {
		if (window.innerWidth < 1001) {
			this.el.classList.add('is-mobile');
		} else {
			this.el.classList.remove('is-mobile');
		}
	}

	selectVideo(e) {
		e.preventDefault();
		console.log('select');
		if (this.el.classList.contains('is-mobile') &&
		    ! this.el.classList.contains('is-expanded')) {
			this.el.classList.add('is-expanded');
			return;
		}
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

function resize() {
	let vw = document.documentElement.clientWidth / 100;
	let vh = document.documentElement.clientHeight / 100;
	document.documentElement.style.setProperty('--vw', `${vw}px`);
	document.documentElement.style.setProperty('--vh', `${vh}px`);
}
resize();
window.addEventListener('resize', resize);
