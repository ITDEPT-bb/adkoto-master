import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: "class",
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.vue",
    ],

    theme: {
        extend: {
            backgroundImage: {
                hero: "url('/assets/bg/1_bg.jpg')",
                ads: "url('/assets/bg/ads_bg.jpg')",
                aboutUs: "url('/assets/bg/2_bg.jpg')",
                adhikainKoto: "url('/assets/bg/3_bg.jpg')",
                kalakalkoto: "url('/assets/bg/4_bg.jpg')",
                auctionvector: "url('/assets/box/auction_vector.png')",
                tribekoto: "url('/assets/bg/5_bg.jpg')",
                testimonials: "url('/assets/bg/6_bg.jpg')",
                contactus: "url('/assets/bg/7_bg.jpg')",
            },
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
                arial: ["Arial", "sans-serif"],
                calibri: ["Calibri", "sans-serif"],
                garmint: ["Garmint", "sans-serif"],
                circular: ["CircularStd", "sans-serif"],
                bebas: ["Bebas Neue", "sans-serif"],
                boldmarker: ["Bold Marker", "sans-serif"],
                geometr: ["Geometr415 Blk BT", "sans-serif"],
            },
            colors: {
                "blue-primary": "#0076be",
                "blue-hover": "#005a8e",

                brand: {
                    red: "#ED3237",
                    blue: "#0076BE",
                    lightgreen: "#7DD449",
                    green: "#48AD0E",
                },
            },
        },
    },

    plugins: [
        forms,
        "./node_modules/flowbite/**/*.js",
        require("tailwind-scrollbar"),
    ],
};
