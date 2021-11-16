module.exports = {
    important: "#ant-rack-app",
    mode: "jit",
    // 生产环境注释这个，生成ant-rack.css需要这个配置
    // corePlugins: {
    //     preflight: false
    // },
    purge: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue"
    ],
    darkMode: false, // or 'media' or 'class'
    theme: {
        extend: {}
    },
    variants: {
        extend: {}
    },
    // 生成ant-rack.css需要注释下面这行
    plugins: [require("@tailwindcss/forms")]
};
