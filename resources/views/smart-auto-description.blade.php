<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>💡What are smart auto upsells and how to use them within Ant Rack</title>
        <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    </head>

    <body>
        <div class="grid grid-cols-7 p-6">
            <div class="flex flex-col lg:col-start-3 lg:col-span-3 sm:col-span-7">
                <div class="mt-8 mb-4 text-3xl font-bold">
                    What are the Smart Auto-Upsells and how to use them - Ant Rack
                </div>

                <div class="flex items-start p-6 mt-4 bg-gray-100 rounded">
                    <div class="text-xl">👉</div>
                    <div class="ml-3 text-gray-600"> Everything about our most advanced and fully auto-pilot upselling feature.</div>
                </div>

                <div class="mt-4 text-gray-700">
                    Smart Auto-Upsells are something we are really proud of as we are the first upsell app on the Shopify platform which does something like that.
                </div>

                <div class="mt-4 text-gray-700">
                    Basically, it's a fully automated upsell system that will allow you to enable <span class="font-bold">relevant upsells</span> for all products in your store.
                </div>

                <div class="flex items-start p-6 mt-4 bg-gray-100 rounded">
                    <div class="text-xl">💡</div>
                    <div class="ml-3 text-gray-600">
                        How? It's getting the most relevant product from the Shopify Product Recommendations API. Shopify’s AI algorithm uses the store’s data to provide smarter recommendations based on product descriptions and customer behavior.
                    </div>
                </div>

                <div class="mt-4 text-gray-700">
                    To enable Smart Auto-Upsells simply follow the steps below.
                </div>

                <div class="mt-4 text-xl font-bold text-gray-800">
                    Step 1. Select Smart Auto-Upsell offer
                </div>

                <div class="mt-2 text-gray-700">
                    Once the app is installed the first page you will see is the upsell offer selection.
                </div>

                <div class="mt-2 text-gray-700">
                    Smart Auto-Upsell should be the second one in the list. Click on <span class="w-20 px-3 py-2 text-sm font-light text-white bg-green-700 border border-green-800 rounded-sm">Select</span> to set it up.
                </div>

                <div class="mt-2">
                    <img src="/images/smart-step1.png" alt="step1"/>
                </div>

                <div class="mt-4 text-gray-700">
                    Alternatively, you can get to this overview by clicking on the <span class="w-20 px-3 py-2 text-sm font-light text-white bg-green-700 border border-green-800 rounded-sm">New Upsell</span> button.
                </div>

                <div class="mt-4 text-xl font-bold text-gray-800">
                    Step 2.1. Set upsell offer name and description
                </div>

                <div class="mt-4 text-gray-700">
                    At the top of the upsell settings you can enter <span class="px-3 py-1 text-sm font-light text-red-500 bg-gray-100 rounded">Upsell Name</span> which is used for your internal reference only. You can also leave as is and use the predefined one.
                </div>

                <div class="mt-4 text-gray-700">
                    Below the name there is <span class="px-3 py-1 text-sm font-light text-red-500 bg-gray-100 rounded">Description</span> field that will appear below the product on the pop-up. Typically you can use it to motivate customers to add the product to their cart.
                </div>

                <div class="mt-4 text-gray-700">
                    Below description you can find <span class="px-3 py-1 text-sm font-light text-red-500 bg-gray-100 rounded">Product count</span> field, where you can define a number of upsell products to be displayed on the pop-up window.
                </div>

                <div class="mt-4 text-gray-700">
                    There is also an option to <span class="px-3 py-1 text-sm font-light text-red-500 bg-gray-100 rounded">Apply discount</span> if you want the smart upsells to be discounted.
                </div>

                <div class="mt-2">
                    <img class="w-80" src="/images/smart-step2-1.png" alt="step2-1"/>
                </div>

                <div class="mt-4 text-xl font-bold text-gray-800">
                    Step 2.2. Set upsell targeting
                </div>

                <div class="mt-4 text-gray-700">
                    In the section called <span class="px-3 py-1 text-sm font-light text-red-500 bg-gray-100 rounded">Display for</span> you need to select when you want this upsell to be displayed.
                </div>

                <div class="mt-4 text-gray-700">
                    You can target combination of specific collections or products. <span class="font-bold">By default</span> All Products are selected.
                </div>

                <div class="mt-2">
                    <img class="w-80" src="/images/smart-step2-2.png" alt="">
                </div>

                <div class="mt-4 text-xl font-bold text-gray-800">
                    Step 2.3. Set upsell targeting (Additional settings)
                </div>

                <div class="mt-4 text-gray-700">
                    Under targeting section there are more settings which can help you better customize your Smart Upsell offer. You can display them by clicking on <span class="px-3 py-1 text-sm font-light text-red-500 bg-gray-100 rounded">Additional settings</span>.
                </div>

                <div class="mt-4 text-gray-700">
                    You can control the upsell position on the pop-up in case of multiple upsells by configurating the <span class="px-3 py-1 text-sm font-light text-red-500 bg-gray-100 rounded">Upsell position</span>.
                </div>

                <div class="flex p-6 mt-4 bg-gray-100 rounded">
                    <div class="text-xl">
                        💡
                    </div>
                    <div class="ml-3 text-gray-600">
                        For example you are upselling 4 products on the pop-up and you want to order them. The one with the lowest number will be displayed in the first row, the second lowest number in the second row, etc.
                    </div>
                </div>

                <div class="mt-4 text-gray-700">
                    In the <span class="px-3 py-1 text-sm font-light text-red-500 bg-gray-100 rounded">Display for specified price range</span> you can set the price limit when the upsell should appear.
                </div>

                <div class="flex p-6 mt-4 bg-gray-100 rounded">
                    <div class="text-xl">
                        💡
                    </div>
                    <div class="ml-3 text-gray-600">
                        For example you can target products which cost more than $50 or from ¥10 to ¥100.
                    </div>
                </div>

                <div class="mt-4 text-gray-700">
                    You can read more about other settings in our guide <a class="text-blue-500 underline" href="/how-to">How to create your first upsell</a>.
                </div>

                <div class="mt-2">
                    <img class="w-80" src="/images/smart-step2-3.png" alt=""/>
                </div>

                <div class="mt-4 text-xl font-bold text-gray-800">
                    Step 3. Save and activate the upsell offer
                </div>

                <div class="mt-4 text-gray-700">
                    Now just click on the <span class="w-20 px-3 py-2 text-sm font-light text-white bg-green-700 rounded">Save</span> button at the right-bottom part of the page. And that's it, your upsell offer is now live. Congratulations!
                </div>

                <div class="mt-2">
                    <img src="/images/smart-step3.png" alt="">
                </div>

                <div class="flex p-6 mt-4 bg-gray-100 rounded">
                    <div class="text-xl">
                        💡
                    </div>
                    <div class="flex flex-col ml-3 text-gray-600">
                        To make sure your actived the offer. the list page will display the status of all your upsells
                        <div class="mt-2">
                            <img src="/images/smart-step3-1.png" alt="">
                        </div>
                    </div>
                </div>

                <div class="mt-4 text-gray-700">
                    To get more products/services on the pop-up simply create a new offer and use the same targeting.
                </div>

                <div class="mt-4 text-xl font-bold text-gray-800">
                    Step 4. Testing the upsell offer on the live site
                </div>

                <div class="mt-4 text-gray-700">
                    As a last step we recommend to go on your site and check the upsell pop-up yourself. Simply go to any product page based on your targeting settings, wait a couple of seconds till it's fully loaded and click on the <span class="px-3 py-1 text-sm font-light text-red-500 bg-gray-100 rounded">Add to Cart</span> button.
                </div>

                <div class="mt-4 text-gray-700">
                    Based on the number of upsell offers, your pop-up should look like this:
                </div>

                <div class="mt-2">
                    <img src="/images/smart-step4.png" alt="">
                </div>

                <div class="flex p-6 mt-4 bg-gray-100 rounded">
                    <div class="text-xl">
                        ❤️
                    </div>
                    <div class="flex flex-col ml-3 text-gray-600">
                        <div class="font-bold text-gray-800"> Happy upselling.</div>
                        <div class="text-gray-700">
                            <span class="italic text-gray-600">Your</span> <a class="italic text-blue-500 underline" target="_blank" href="https://apps.shopify.com/partners/9069">PB Apps</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
