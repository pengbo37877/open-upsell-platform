// create a new div with id "ant-rack-app" and append it to document.body
var antRackApp = document.createElement("div");
antRackApp.id = "ant-rack-app";
antRackApp.style = "z-index:2147483647";
document.body.append(antRackApp);

// check if the page is products page
var isProductsPage = location.pathname.includes("/products/");

// local currency
var antRackLocalCurrency = window.localStorage.getItem(
    "ant-rack-local-currency"
);
if (!antRackLocalCurrency) {
    fetch(upsellRockBaseUrl + "/ip?shop=" + upsellRockShopDomain)
        .then(response => {
            return response.json();
        })
        .then(json => {
            if (json.geoplugin_currencyCode) {
                antRackLocalCurrency = json.geoplugin_currencyCode;
                window.localStorage.setItem(
                    "ant-rack-local-currency",
                    antRackLocalCurrency
                );
            } else {
                antRackLocalCurrency = shopCurrency;
                window.localStorage.setItem(
                    "ant-rack-local-currency",
                    antRackLocalCurrency
                );
            }
        });
}

// find ATC
var addToCartButtons = document.querySelectorAll(
    'form[action^="/cart/add"] [type^="submit"]'
);
if (addToCartButtons.length > 0) {
    addToCartButtons.forEach(addToCartButton => {
        var newAddToCartButton = addToCartButton.cloneNode(true);
        addToCartButton.style.display = "none";
        addToCartButton.classList.add("ant-rack");
        addToCartButton.classList.add("ant-rack-original");
        newAddToCartButton.classList.add("ant-rack");
        newAddToCartButton.classList.add("ant-rack-cloned");
        addToCartButton.parentNode.insertBefore(
            newAddToCartButton,
            addToCartButton.nextSibling
        );
        newAddToCartButton.addEventListener("click", function(e) {
            console.log("newAddToCartButton", "click");
            e.stopPropagation();
            e.preventDefault();

            // add current product to cart
            // addCurrentVariantToCart();

            // write ant rack max popup limit
            var antRackMaxPopupLimit = JSON.parse(
                localStorage.getItem("ant-rack-max-popup-limit")
            );
            if (!antRackMaxPopupLimit) {
                antRackMaxPopupLimit = 1;
                localStorage.setItem(
                    "ant-rack-max-popup-limit",
                    antRackMaxPopupLimit
                );
            } else if (
                upsellRockSetting.max_popup_session_views > 0 &&
                upsellRockSetting.max_popup_session_views <=
                    antRackMaxPopupLimit
            ) {
                // original click
                var originalAddToCartButton = document.querySelector(
                    ".ant-rack.ant-rack-original"
                );
                if (originalAddToCartButton) {
                    originalAddToCartButton.click();
                    return;
                }
            }
            // update ant rack popup localStorage
            antRackMaxPopupLimit++;
            localStorage.setItem(
                "ant-rack-max-popup-limit",
                antRackMaxPopupLimit
            );

            //
            // show antRackApp
            var antBgWrap = document.getElementById("ant-bg-wrap");
            var antBgOverlay = document.getElementById("ant-bg-overlay");
            var antModalPanel = document.getElementById("ant-modal-panel");

            antBgWrap.classList.remove("hidden");

            setTimeout(() => {
                antBgOverlay.classList.remove("opacity-0");
                antBgOverlay.classList.add("opacity-100");

                antModalPanel.classList.remove("opacity-0");
                antModalPanel.classList.remove("translate-y-4");
                antModalPanel.classList.remove("sm:scale-95");
                antModalPanel.classList.remove("sm:translate-y-0");
                antModalPanel.classList.add("opacity-100");
                antModalPanel.classList.add("translate-y-0");
                antModalPanel.classList.add("sm:scale-100");
            }, 200);

            // track view
            var upsells = JSON.parse(
                document.getElementById("upsells").innerText
            );
            var token = localStorage.getItem("ant-rack-token");
            if (token) {
                trackView(token, upsells, "view");
            }
        });
    });
}

async function addCurrentVariantToCart() {
    let addToCartForm = document.querySelector('form[action="/cart/add"]');
    let formData = new FormData(addToCartForm);

    let res = await fetch("/cart/add.js", {
        method: "POST",
        body: formData
    });
    let json = await res.json();
    return json;
}

// load css and js
if (isProductsPage) {
    var tailwindcss = document.createElement("link");
    tailwindcss.href = antRackCssUrl;
    tailwindcss.rel = "stylesheet";
    tailwindcss.type = "text/css";
    document.head.prepend(tailwindcss);

    var js = document.createElement("script");
    js.src = antRackJsUrl;
    js.type = "text/javascript";
    document.body.append(js);

    // load html
    fetch("/cart.js")
        .then(res => res.json())
        .then(json => {
            localStorage.setItem("ant-rack-token", json.token);
            loadAntRackHtml(json);
            updateAntRackAttribute(json);
        });
    // get recommendations
    getCurrentProduct().then(product => {
        getShopifyRecommendedProducts(product.id);
    });
}

async function loadAntRackHtml(cart) {
    // load html
    console.log("loadAntRackHtml");
    // get variant_id
    let addToCartForm = document.querySelector('form[action="/cart/add"]');
    let formData = new FormData(addToCartForm);
    let variant_id = 0;
    formData.forEach((v, k) => {
        if (k == "id") {
            variant_id = v;
        }
    });
    let res = await fetch(upsellRockBaseUrl + "/ant_rack", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            shop: upsellRockShopDomain,
            cart: cart,
            variant: variant_id
        })
    });
    let html = await res.text();
    if (!html || html.length == 0) {
        resetATCs();
        return;
    }
    antRackApp.innerHTML = html;
    //close event
    setTimeout(() => {
        var antBgWrap = document.getElementById("ant-bg-wrap");
        var antBgOverlay = document.getElementById("ant-bg-overlay");
        var antModalPanel = document.getElementById("ant-modal-panel");
        var antCancelBtns = document.querySelectorAll(".ant-cancel-btn");
        antCancelBtns.forEach(antCancelBtn => {
            antCancelBtn.addEventListener("click", function(e) {
                antModalPanel.classList.remove("ease-out");
                antModalPanel.classList.remove("duration-300");
                antModalPanel.classList.remove("opacity-100");
                antModalPanel.classList.remove("translate-y-0");
                antModalPanel.classList.remove("sm:scale-100");
                antModalPanel.classList.add("ease-in");
                antModalPanel.classList.add("duration-200");
                antModalPanel.classList.add("opacity-0");
                antModalPanel.classList.add("translate-y-4");
                antModalPanel.classList.add("sm:translate-y-0");
                antModalPanel.classList.add("sm:scale-95");

                antBgOverlay.classList.remove("ease-out");
                antBgOverlay.classList.remove("duration-300");
                antBgOverlay.classList.remove("opacity-100");
                antBgOverlay.classList.add("ease-in");
                antBgOverlay.classList.add("duration-200");
                antBgOverlay.classList.add("opacity-0");
                setTimeout(() => {
                    antBgWrap.classList.add("hidden");
                    console.log("outside cancel");

                    // dell with close action
                    if (upsellRockSetting.close_action == "") {
                        // add current product to cart
                        var antRackOriginal = document.querySelector(
                            ".ant-rack.ant-rack-original"
                        );
                        if (antRackOriginal) {
                            antRackOriginal.click();
                        }
                    } else if (upsellRockSetting.close_action == "close") {
                        addCurrentVariantToCart();
                    } else if (
                        upsellRockSetting.close_action == "redirect_to_cart"
                    ) {
                        addCurrentVariantToCart();
                        setTimeout(() => {
                            location.href = "/cart";
                        }, 200);
                    } else if (
                        upsellRockSetting.close_action == "redirect_to_checkout"
                    ) {
                        addCurrentVariantToCart();
                        setTimeout(() => {
                            location.href = "/checkout";
                        }, 200);
                    }
                }, 200);
            });
        });
        var upsells = JSON.parse(document.getElementById("upsells").innerText);
        trackView(cart.token, upsells, "load");
    }, 500);
}

function resetATCs() {
    var newATCs = document.querySelectorAll(".ant-rack.ant-rack-cloned");
    newATCs.forEach(atc => {
        atc.style.display = "none";
    });

    var originalATCs = document.querySelectorAll(".ant-rack.ant-rack-original");
    originalATCs.forEach(atc => {
        atc.style.display = "";
    });
}

function trackView(token, upsells, event_type) {
    fetch(upsellRockBaseUrl + "/track", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            shop: upsellRockShopDomain,
            cart_token: token,
            event_type: event_type,
            upsell_rocks: upsells.map(upsell => upsell.id),
            data: {},
            stats_at: new Date().getTime()
        })
    })
        .then(res => {
            return res.json();
        })
        .then(json => {
            console.log(json);
        });
}

var currentProduct = {};
async function getCurrentProduct() {
    let res = await fetch(location.pathname + ".js");
    let json = await res.json();
    currentProduct = json;
    var div = document.createElement("div");
    div.style.display = "none";
    div.id = "current-product";
    div.innerText = JSON.stringify(json);
    document.body.append(div);
    return json;
}

var allRecommendedProducts = [];
async function getShopifyRecommendedProducts(pid) {
    let res = await fetch("/recommendations/products.json?product_id=" + pid);
    let json = await res.json();
    let products = json.products;

    // for test
    // if (products.length == 0) {
    //     json = {
    //         products: [
    //             {
    //                 id: 35,
    //                 title: "Gorgeous Silk Coat",
    //                 handle: "gorgeous-silk-coat",
    //                 description: null,
    //                 published_at: "2019-02-26T11:34:58-05:00",
    //                 created_at: "2019-02-26T11:34:58-05:00",
    //                 vendor: "Marge Group",
    //                 type: "Outdoors",
    //                 tags: [],
    //                 price: 380000,
    //                 price_min: 380000,
    //                 price_max: 790000,
    //                 available: true,
    //                 price_varies: true,
    //                 compare_at_price: null,
    //                 compare_at_price_min: 0,
    //                 compare_at_price_max: 0,
    //                 compare_at_price_varies: false,
    //                 variants: [
    //                     {
    //                         id: 69,
    //                         title: "Small Aluminum Knife",
    //                         option1: "Small Aluminum Knife",
    //                         option2: null,
    //                         option3: null,
    //                         sku: "",
    //                         requires_shipping: true,
    //                         taxable: true,
    //                         featured_image: null,
    //                         available: true,
    //                         name: "Gorgeous Silk Coat - Small Aluminum Knife",
    //                         public_title: "Small Aluminum Knife",
    //                         options: ["Small Aluminum Knife"],
    //                         price: 790000,
    //                         weight: 9500,
    //                         compare_at_price: null,
    //                         inventory_management: "shopify",
    //                         barcode: null
    //                     },
    //                     {
    //                         id: 70,
    //                         title: "Heavy Duty Bronze Shoes",
    //                         option1: "Heavy Duty Bronze Shoes",
    //                         option2: null,
    //                         option3: null,
    //                         sku: "",
    //                         requires_shipping: true,
    //                         taxable: true,
    //                         featured_image: null,
    //                         available: true,
    //                         name:
    //                             "Gorgeous Silk Coat - Heavy Duty Bronze Shoes",
    //                         public_title: "Heavy Duty Bronze Shoes",
    //                         options: ["Heavy Duty Bronze Shoes"],
    //                         price: 380000,
    //                         weight: 2200,
    //                         compare_at_price: null,
    //                         inventory_management: "shopify",
    //                         barcode: null
    //                     }
    //                 ],
    //                 images: [],
    //                 featured_image: null,
    //                 options: [
    //                     {
    //                         name: "Color or something",
    //                         position: 1,
    //                         values: [
    //                             "Small Aluminum Knife",
    //                             "Heavy Duty Bronze Shoes"
    //                         ]
    //                     }
    //                 ],
    //                 url:
    //                     "/products/gorgeous-silk-coat?pr_choice=default&pr_prod_strat=copurchase&pr_rec_pid=35&pr_ref_pid=17&pr_seq=alternating"
    //             },
    //             {
    //                 id: 13,
    //                 title: "Gorgeous Wooden Computer",
    //                 handle: "gorgeous-wooden-computer",
    //                 description: null,
    //                 published_at: "2019-02-26T11:34:15-05:00",
    //                 created_at: "2019-02-26T11:34:15-05:00",
    //                 vendor: "Purdy Inc",
    //                 type: "Garden",
    //                 tags: [],
    //                 price: 930000,
    //                 price_min: 930000,
    //                 price_max: 1730000,
    //                 available: true,
    //                 price_varies: true,
    //                 compare_at_price: null,
    //                 compare_at_price_min: 0,
    //                 compare_at_price_max: 0,
    //                 compare_at_price_varies: false,
    //                 variants: [
    //                     {
    //                         id: 25,
    //                         title: "Mediocre Silk Bottle",
    //                         option1: "Mediocre Silk Bottle",
    //                         option2: null,
    //                         option3: null,
    //                         sku: "",
    //                         requires_shipping: true,
    //                         taxable: true,
    //                         featured_image: null,
    //                         available: true,
    //                         name:
    //                             "Gorgeous Wooden Computer - Mediocre Silk Bottle",
    //                         public_title: "Mediocre Silk Bottle",
    //                         options: ["Mediocre Silk Bottle"],
    //                         price: 1730000,
    //                         weight: 5700,
    //                         compare_at_price: null,
    //                         inventory_management: "shopify",
    //                         barcode: null
    //                     },
    //                     {
    //                         id: 26,
    //                         title: "Lightweight Paper Shirt",
    //                         option1: "Lightweight Paper Shirt",
    //                         option2: null,
    //                         option3: null,
    //                         sku: "",
    //                         requires_shipping: true,
    //                         taxable: true,
    //                         featured_image: null,
    //                         available: true,
    //                         name:
    //                             "Gorgeous Wooden Computer - Lightweight Paper Shirt",
    //                         public_title: "Lightweight Paper Shirt",
    //                         options: ["Lightweight Paper Shirt"],
    //                         price: 930000,
    //                         weight: 6600,
    //                         compare_at_price: null,
    //                         inventory_management: "shopify",
    //                         barcode: null
    //                     }
    //                 ],
    //                 images: [],
    //                 featured_image: null,
    //                 options: [
    //                     {
    //                         name: "Color or something",
    //                         position: 1,
    //                         values: [
    //                             "Mediocre Silk Bottle",
    //                             "Lightweight Paper Shirt"
    //                         ]
    //                     }
    //                 ],
    //                 url:
    //                     "/products/gorgeous-wooden-computer?pr_choice=default&pr_prod_strat=description&pr_rec_pid=13&pr_ref_pid=17&pr_seq=alternating"
    //             }
    //         ]
    //     };
    //     products = json.products;
    // }

    if (products.length > 0) {
        allRecommendedProducts = allRecommendedProducts.concat(products);
    }
    console.log("allRecommendedProducts", allRecommendedProducts);
    var div = document.createElement("div");
    div.id = "ant-shopify-recommendations";
    div.innerText = JSON.stringify(allRecommendedProducts);
    div.style.display = "none";
    document.body.append(div);
}


async function updateAntRackAttribute(cart) {
    var res = await fetch('/cart/update.js', {
        method: 'POST',
        headers: { "Content-Type": "application/json" },
        'body': JSON.stringify({
            attributes: {
                antrack_token: cart.token
            }
        })
    });
    var json = await res.json();
    return json;
}
