import "../scss/main.scss";
import "../scss/blocks.scss";
import * as scroller from "scrollama";

scroller
	.default()
	.setup({
		step: ".scrolly-image-block .step",
	})
	.onStepEnter((response) => {
		console.log(response.element);
	});
