/** @type {import('tailwindcss').Config} */
module.exports = {
    // important: true,
    important: "#app",
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./node_modules/flowbite/**/*.js",
    ],
    theme: {
        extend: {
            colors: {
                yellow: {
                    0: "#FFB344",
                    1: "#FAAE3F",
                    2: "#F5A93A",
                },
                red: {
                    0: "#E05D5D",
                },
                grey: {
                    0: "#D9D9D9",
                    1: "#D4D4D4",
                    2: "#CFCFCF",
                },
                tosca: {
                    0: "#00A19D",
                    1: "#0F928E",
                    2: "#00837F",
                    20: "#32D3CF",
                },
                green: {
                    0: "#5CB85C",
                    1: "#57B357",
                    2: "#52AE52",
                },
                blue: {
                    0: '#068DA9'
                }
            },
            borderWidth: {
                1: "1px",
                1.5: "1.5px",
            },
            minWidth: {
                150: "130px",
                200: "200px",
                250: "250px",
                300: "300px",
                800: "800px",
                20: "20px",
            },
            maxWidth: {
                150: "130px",
                415: "415px",
                200: "200px",
            },
            fontFamily: {
                'poppins': ['Poppins', 'sans-serif']
            }
        },
    },
    plugins: [require("flowbite/plugin")],
};
