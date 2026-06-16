<?php
session_start();
// ডাটাবেস বা অন্য কিছু লাগলে এখানে ইনক্লুড করতে পারো
// require 'db.php'; 

$current_page = 'faq.php';
?>

<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - HarvestIQ Help Center</title>
    
    <!-- CSS Links (তোমার স্টাইলশিটগুলো) -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/all.min.css">
    <link rel="stylesheet" href="style.css?v=3.0">
    
    <style>
        /* FAQ স্পেশাল গ্লাস ইফেক্ট ডিজাইন */
        .faq-wrapper {
            padding: 140px 5% 80px; /* নেভবারের জন্য উপরে স্পেস */
            min-height: 80vh;
            position: relative;
            z-index: 1;
        }

        .faq-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .faq-header h1 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 800;
            color: var(--sz-text-dark, #f8fafc);
            font-size: 2.5rem;
        }

        .faq-header p {
            color: var(--sz-text-muted, #94a3b8);
            font-size: 1.1rem;
            max-width: 600px;
            margin: 10px auto 0;
        }

        /* Accordion Customization */
        .faq-accordion .accordion-item {
            background: rgba(30, 41, 59, 0.4);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px !important;
            margin-bottom: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        [data-theme="light"] .faq-accordion .accordion-item {
            background: rgba(255, 255, 255, 0.7);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .faq-accordion .accordion-button {
            background: transparent;
            color: var(--sz-text-dark, #f8fafc);
            font-weight: 600;
            font-size: 1.1rem;
            padding: 20px 24px;
            box-shadow: none !important;
            font-family: 'Inter', sans-serif;
        }

        .faq-accordion .accordion-button:not(.collapsed) {
            color: #16a34a; /* HarvestIQ Green */
            background: rgba(22, 163, 74, 0.05);
        }

        .faq-accordion .accordion-button::after {
            filter: invert(1) grayscale(100%) brightness(200%);
            transition: transform 0.3s ease;
        }

        [data-theme="light"] .faq-accordion .accordion-button::after {
            filter: none;
        }

        .faq-accordion .accordion-button:not(.collapsed)::after {
            filter: invert(50%) sepia(100%) saturate(300%) hue-rotate(80deg);
        }

        .faq-accordion .accordion-body {
            color: var(--sz-text-muted, #cbd5e1);
            padding: 0 24px 24px 24px;
            line-height: 1.6;
            font-size: 0.95rem;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            margin-top: 10px;
            padding-top: 15px;
        }

        [data-theme="light"] .faq-accordion .accordion-body {
            color: #475569;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body>

<!-- === 1. NAVBAR INCLUDE === -->
<!-- (যদি তোমার nav.php আলাদা ফাইল থাকে, তবে নিচের লাইনটি আনকমেন্ট করো, 
     অথবা এখানে তোমার মেইন পেজ থেকে নেভবারের পুরো কোডটুকু পেস্ট করে দাও) -->
<?php include 'nav.php'; ?> 


<!-- === 2. FAQ MAIN CONTENT === -->
<div class="faq-wrapper">
    <div class="container" style="max-width: 900px;">
        
        <div class="faq-header">
            <h1><i class="fa-solid fa-circle-question text-success me-2"></i> Frequently Asked Questions</h1>
            <p>Simple answers to common questions about using the HarvestIQ platform.</p>
        </div>

        <div class="accordion faq-accordion" id="harvestIqFaq">
            
            <!-- Question 1 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        <i class="fa-solid fa-seedling me-3 text-success"></i> What is HarvestIQ and how does it help farmers?
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#harvestIqFaq">
                    <div class="accordion-body">
                        HarvestIQ is an intelligent agriculture advisory platform. We help farmers make better decisions by providing real-time weather forecasts, accurate market prices, and AI-driven crop recommendations based on your soil type and season. Our goal is to increase your yield and profit.
                    </div>
                </div>
            </div>

            <!-- Question 2 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        <i class="fa-solid fa-wifi me-3 text-success"></i> Do I need internet all the time to use the app?
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#harvestIqFaq">
                    <div class="accordion-body">
                        <strong>No!</strong> We understand that farms often have poor network connectivity. HarvestIQ is built with a "Mobile-first, offline-capable" design. Once you load the app, key features and cached advisories will still be available even if you lose your internet connection.
                    </div>
                </div>
            </div>

            <!-- Question 3 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingThree">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        <i class="fa-solid fa-language me-3 text-success"></i> Can I use HarvestIQ in my local language?
                    </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#harvestIqFaq">
                    <div class="accordion-body">
                        Yes, absolutely. We have integrated a language translation tool directly into the platform. You can click the language icon in the menu to switch the entire application to Bengali, Hindi, or any other regional language for easier understanding.
                    </div>
                </div>
            </div>

            <!-- Question 4 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingFour">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                        <i class="fa-solid fa-indian-rupee-sign me-3 text-success"></i> Is this service free for farmers?
                    </button>
                </h2>
                <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#harvestIqFaq">
                    <div class="accordion-body">
                        Yes, the core advisory features, weather radar, and crop inventory recommendations are completely free for our farmers. We believe that critical agricultural data should be accessible to everyone without barriers.
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

<!-- === 3. FOOTER INCLUDE === -->
<!-- (যদি তোমার footer.php আলাদা ফাইল থাকে, তবে নিচের লাইনটি আনকমেন্ট করো, 
     অথবা এখানে তোমার মেইন পেজ থেকে ফুটারের পুরো কোডটুকু পেস্ট করে দাও) -->
<?php include 'footer.php'; ?>

<!-- Bootstrap JS -->
<script src="assets/js/bootstrap.bundle.min.js"></script>
<!-- Theme JS -->
<script src="assets/js/theme.js"></script>

</body>
</html>