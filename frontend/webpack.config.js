const path = require('path');
const webpack = require('webpack');

module.exports = {
    entry: "./src/index.js",
    devtool: 'inline-source-map',
    module: {
        rules: [
            {
                test: /\.css$/,
                use: [
                    'style-loader',
                    'css-loader',
                ]
            }
        ]
    },
    "plugins": [
        new webpack.ProvidePlugin({
            $: "jquery",
            jQuery: "jquery",
            Popper: "popper.js"
        }),
        new webpack.EnvironmentPlugin([
            'NODE_ENV'
        ]),
    ],
    output: {
        filename: "bundle.js",
        path: path.resolve(__dirname, './dist'),
        publicPath: "/dist"
    },
};