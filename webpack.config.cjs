const path = require('path');

module.exports = {
	entry: "./assets/ts/main.ts",
	output: {
		filename: "main.js",
		path: path.resolve(__dirname, 'dist')
	},
	devtool: 'inline-source-map',
	mode: 'development',
	module: {
		rules: [
			{
				test: /\.ts$/,
				use: 'ts-loader',
				exclude: /node_modules/
			},
			{
				test: /\.scss$/,
				use: [
					'style-loader',
					'css-loader',
					'sass-loader'
				]
			},
			{
				test: /\.(woff(2)?|ttf|eot|svg)$/,
				use: 'file-loader'
			}
		]
	},
	resolve: {
		extensions: ['.ts', '.js']
	},
};