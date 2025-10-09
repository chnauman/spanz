@extends('layouts.master')
@section('title', 'Home - Spanz')
@section('content')
@include('components.mainheader')

<div class="text-blue-950 font-semibold text-xl flex justify-center py-5 text-center px-3">
        <span>For 125+ years, SPANZ has connected buyers with industrial suppliers</span>        
    </div>

    <!-- Buyers Section -->
    <div class="flex flex-col-reverse lg:flex-row justify-evenly items-center px-5 lg:px-20 py-12">    
        <!-- Left Text Section -->
        <div class=" text-center lg:text-left mt-8 lg:mt-0">
            <span class="text-white bg-blue-950 px-5 py-1 rounded-full inline-block">For Buyers</span>        
            <h1 class="text-2xl sm:text-3xl lg:text-4xl text-blue-950 py-5 mx-auto lg:mx-0 lg:w-[26rem]">Every 10 seconds, a buyer finds what they need on SPANZ</h1>        
            <ul class="list-disc pl-5 text-blue-950 space-y-2 text-left inline-block lg:block">
                <li>Access our network of 500,000+ trusted suppliers</li>
                <li>Filter by Distance, Certification, and more</li>
                <li>Evaluate Supplier Capabilities and Services</li>
                <li>Get Direct Quotes</li>
                <li>Source Parts and Services Today</li>
            </ul>        
            <button class="bg-[#0D6AED] text-white px-4 py-2 mt-5">Search for a Supplier</button>
        </div>    
        <!-- Right Image Section -->
        <div class="w-full lg:w-1/2 flex justify-center mt-8 lg:mt-0">
            <img src="{{ asset('spanz-img/for-buyers.webp') }}" alt="For Buyers" class="w-full max-w-sm sm:max-w-md lg:w-[29rem]">
        </div>
    </div>

    <!-- Suppliers Section -->
    <div class="flex flex-col-reverse lg:flex-row justify-evenly items-center px-5 lg:px-20 py-12">
        <!-- Left: Image -->
        <div class="flex mt-8 lg:mt-0">
            <img src="{{ asset('spanz-img/for-suppliers.webp') }}" alt="For Suppliers" class="w-full max-w-sm sm:max-w-md lg:w-[29rem]">
        </div>
        <!-- Right: Text -->
        <div class="w-full lg:w-1/2 text-center flex justify-center lg:text-left mt-8 lg:mt-0">
            <div>
                <span class="text-white bg-blue-950 px-5 py-1 rounded-full inline-block">For Suppliers</span>
                <h1 class="text-2xl sm:text-3xl lg:text-4xl text-blue-950 py-5 mx-auto lg:mx-0 lg:w-[26rem]">Make every marketing dollar count. Get discovered on SPANZ.</h1>
                <ul class="list-disc pl-5 text-blue-950 space-y-2 text-left inline-block lg:block">
                    <li>Join a trusted Network of Suppliers</li>
                    <li>Customize your Ad budget</li>
                    <li>Define your target market</li>
                    <li>Pay for what you get</li>
                    <li>Drive results & track your progress</li>
                </ul>

                <button class="bg-[#0D6AED] text-white px-4 py-2 mt-5">Get Started Today</button>
            </div>
        </div>
    </div>
    <div class="bg-gray-100 py-10">
        <div class="justify-center flex flex-wrap text-center px-4">
            <span>Join North America's top companies actively searching on </span>
            <span class="text-[#0D6AED] font-semibold ml-1">SPANZ</span>
        </div>

        <!-- Logo section -->
        <div class="flex flex-wrap justify-center gap-6 sm:gap-10 my-5 px-4">
            <img src="{{ asset('spanz-img/Boeing_full_logo.svg') }}" alt="Boeing" class="w-20 sm:w-24">
            <img src="{{ asset('spanz-img/3M_wordmark.svg') }}" alt="3M" class="w-16 sm:w-24">
            <img src="{{ asset('spanz-img/general-dynamics-logo.svg') }}" alt="General Dynamics" class="w-24 sm:w-28">
            <img src="{{ asset('spanz-img/NASA_logo.svg') }}" alt="NASA" class="w-20 sm:w-24">
            <img src="{{ asset('spanz-img/Kraft_logo.svg') }}" alt="Kraft" class="w-20 sm:w-24">
            <img src="{{ asset('spanz-img/Rockwell_Collins_logo.svg') }}" alt="Rockwell" class="w-24 sm:w-28">
        </div>

        <div class="flex justify-center">
            <a href="{{ route('company.register') }}" class="bg-[#0D6AED] text-white px-4 py-2 mt-5 rounded-sm hover:bg-blue-700 transition-colors">
                Claim your company profile
            </a>
        </div>
    </div>
    <div class="flex flex-col md:flex-row lg:justify-center text-blue-950 px-4 md:pl-40 pt-10 gap-6 md:gap-0">
        <div class="w-full md:w-[60rem]">
            <p class="text-2xl sm:text-3xl md:text-4xl pb-4 md:pb-6">For industry. For 125+ Years.</p>
            <span class="text-base sm:text-xl md:text-2xl">Spanz connects buyers and suppliers to inform strategic decision-making. build supply chains and grow business.</span>
        </div>
        <div class="flex items-center justify-start md:justify-center mt-4 md:mt-0">
            <button class="bg-[#0D6AED] text-white px-4 py-2 rounded-sm w-full md:w-auto">
                Learn More About Us
            </button>
        </div>
    </div>
    <div class="bg-gray-100 mt-10 pb-10">
        <div class="flex justify-center">
            <h1 class="text-2xl sm:text-3xl my-10 lg:text-4xl text-blue-950 py-5 mx-auto lg:mx-0">Browse Supplier Categories</h1>            
        </div>
        <!-- grid layout for all categories -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-8 w-full px-5 md:px-28 lg:px-28 ml-auto mr-auto">
            <div>
                <div class="font-semibold pb-4">
                    <h2>Adhesives & Sealants</h2>
                </div>
                <div class="text-sm">
                    
                    <ul>
                       <li><a href="/adhesives">Adhesives</a></li>
                        <li><a href="/adhesive-tapes">Adhesive Tapes</a></li>
                        <li><a href="/tapes">Tapes</a></li>
                        <li><a href="/pressure-sensitive-tapes">Pressure Sensitive Tapes</a></li>
                        <li><a href="/adhesive-dispensing-equipment">Adhesive Dispensing Equipment</a></li>
                    </ul>
                </div>
            </div>
            <div>
                <div class="font-semibold pb-4">
                    <h2>Automation & Electronics</h2>
                </div>
                <div class="text-sm">
                    <ul>
                        <li><a href="/automation-equipment">Automation Equipment</a></li>
                        <li><a href="/printed-circuit-boards">Printed Circuit Boards (PCB)</a></li>
                        <li><a href="/electronic-enclosures">Electronic Enclosures</a></li>
                        <li><a href="/cleanrooms">Cleanrooms</a></li>
                        <li><a href="/emi-rfi-shielding">EMI/RFI Shielding</a></li>
                    </ul>
                </div>
            </div>
            <div>
                <div class="font-semibold pb-4">
                    <h2>Chemicals</h2>
                </div>
                <div class="text-sm">
                    <ul>
                        <li><a href="/coating">Coating</a></li>
                        <li><a href="/desiccants">Desiccants</a></li>
                        <li><a href="/corrosion-resistant-coatings">Corrosion Resistant Coatings</a></li>
                        <li><a href="/optical-coatings">Optical Coatings</a></li>
                        <li><a href="/ptfe-coatings">PTFE Coatings</a></li>
                    </ul>
                </div>
            </div>
            <div>
                <div class="font-semibold pb-4 w-44">
                    <h2>Custom Manufacturing & Fabricating</h2>
                </div>
                <div class="text-sm">
                    <ul>
                        <li><a href="/metal-fabrication">Metal Fabrication</a></li>
                        <li><a href="/cnc-machining">CNC Machining</a></li>
                        <li><a href="/metal-stampings">Metal Stampings</a></li>
                        <li><a href="/screw-machine-products">Screw Machine Products</a></li>
                        <li><a href="/tube-fabricating">Tube Fabricating</a></li>
                    </ul>
                </div>
            </div>
            <div>
                <div class="font-semibold pb-4">
                    <h2>Electrical & Power Generation</h2>
                </div>
                <div class="text-sm">
                    <ul>
                        <li><a href="/batteries">Batteries</a></li>
                        <li><a href="/transformers">Transformers</a></li>
                        <li><a href="/magnets">Magnets</a></li>
                        <li><a href="/custom-transformers">Custom Transformers</a></li>
                        <li><a href="/neodymium-magnets">Neodymium Magnets</a></li>
                    </ul>
                </div>
            </div>
            <div>
                <div class="font-semibold pb-4">
                    <h2>Engineering & Consulting</h2>
                </div>
                <div class="text-sm">
                    <ul>
                        <li><a href="/engineering-services">Engineering Services</a></li>
                        <li><a href="/prototypes">Prototypes</a></li>
                        <li><a href="/rapid-prototyping-services">Rapid Prototyping Services</a></li>
                        <li><a href="/product-development">Product Development</a></li>
                        <li><a href="/exporters-importers">Exporters, Importers</a></li>
                    </ul>
                </div>
            </div>
            <div>
                <div class="font-semibold pb-4">
                    <h2>Hardware</h2>
                </div>
                <div class="text-sm ">
                    <ul>
                        <li><a href="/fasteners">Fasteners</a></li>
                        <li><a href="/gaskets">Gaskets</a></li>
                        <li><a href="/bolts">Bolts</a></li>
                        <li><a href="/o-rings">O Rings</a></li>
                        <li><a href="/hinges">Hinges</a></li>
                    </ul>
                </div>
            </div>
            <div>
                <div class="font-semibold pb-4">
                    <h2>Instruments & Controls</h2>
                </div>
                <div class="text-sm">
                    <ul>
                        <li><a href="/laboratory-equipment-supplies">Laboratory Equipment & Supplies</a></li>
                        <li><a href="/flow-meters">Flow Meters</a></li>
                        <li><a href="/sensors">Sensors</a></li>
                        <li><a href="/calibration-services">Calibration Services</a></li>
                        <li><a href="/leak-detectors">Leak Detectors</a></li>
                    </ul>
                </div>
            </div>
            <div>
                <div class="font-semibold pb-4">
                    <h2>Machinery, Tools & Supplies</h2>
                </div>
                <div class="text-sm">
                    <ul>
                        <li>Special & Custom Machinery</li>
                        <li>Bearings</li>
                        <li>Gears</li>
                        <li>Brushes</li>
                        <li>Springs</li>
                    </ul>
                </div>
            </div>
            <div>
                <div class="font-semibold pb-4">
                    <h2>Materials Handling</h2>
                </div>
                <div class="text-sm">
                    <ul>
                        <li><a href="/special-custom-machinery">Special & Custom Machinery</a></li>
                        <li><a href="/bearings">Bearings</a></li>
                        <li><a href="/gears">Gears</a></li>
                        <li><a href="/brushes">Brushes</a></li>
                        <li><a href="/springs">Springs</a></li>
                    </ul>
                </div>
            </div>
            <div>
                <div class="font-semibold pb-4">
                    <h2>Metals & Metal Products</h2>
                </div>
                <div class="text-sm">
                    <ul>
                        <li><a href="/aluminum">Aluminum</a></li>
                        <li><a href="/steel-service-centers">Steel Service Centers</a></li>
                        <li><a href="/stainless-steel">Stainless Steel</a></li>
                        <li><a href="/wire-forms">Wire Forms</a></li>
                        <li><a href="/powdered-metal-parts">Powdered Metal Parts</a></li>
                    </ul>
                </div>
            </div>
            <div>
                <div class="font-semibold pb-4">
                    <h2>Plants & Facility Equipment</h2>
                </div>
                <div class="text-sm">
                    <ul>
                        <li><a href="/electric-heaters">Electric Heaters</a></li>
                        <li><a href="/nameplates">Nameplates</a></li>
                        <li><a href="/industrial-vacuum-cleaners">Industrial Vacuum Cleaners</a></li>
                        <li><a href="/dust-collecting-systems">Dust Collecting Systems</a></li>
                        <li><a href="/noise-control">Noise Control</a></li>
                    </ul>
                </div>
            </div>
            <div>
                <div class="font-semibold pb-4">
                    <h2>Plastics & Rubber</h2>
                </div>
                <div class="text-sm">
                    <ul>
                        <li><a href="/injection-molded-plastics">Injection Molded Plastics</a></li>
                        <li><a href="/molded-plastics">Molded Plastics</a></li>
                        <li><a href="/molded-rubber-goods">Molded Rubber Goods</a></li>
                        <li><a href="/extruded-plastics">Extruded Plastics</a></li>
                        <li><a href="/custom-injection-molded-plastics">Custom Injection Molded Plastics</a></li>
                    </ul>
                </div>
            </div>
            <div>
                <div class="font-semibold pb-4">
                    <h2>Process Equipment</h2>
                </div>
                <div class="text-sm">
                    <ul>
                        <li><a href="/heat-exchangers">Heat Exchangers</a></li>
                        <li><a href="/pressure-vessels">Pressure Vessels</a></li>
                        <li><a href="/misers">Misers</a></li>
                        <li><a href="/ovens">Ovens</a></li>
                        <li><a href="/heating-elements">Heating Elements</a></li>
                    </ul>
                </div>
            </div>
            <div>
                <div class="font-semibold pb-4">
                    <h2>Pumps, Valves & Accessories</h2>
                </div>
                <div class="text-sm">
                    <ul>
                        <li><a href="/ball-valves">Ball Valves</a></li>
                        <li><a href="/pumps">Pumps</a></li>
                        <li><a href="/plastic-tubing">Plastic Tubing</a></li>
                        <li><a href="/stainless-steel-tubing">Stainless Steel Tubing</a></li>
                        <li><a href="/vacuum-pumps">Vacuum Pumps</a></li>
                    </ul>
                </div>
            </div>
            <div>
                <div class="font-semibold pb-4">
                    <h2>Other</h2>
                </div>
                <div class="text-sm">
                    <ul>
                        <li><a href="/apparel">Apparel</a></li>
                        <li><a href="/medical">Medical</a></li>
                        <li><a href="/marine">Marine</a></li>
                        <li><a href="/signs">Signs</a></li>
                        <li><a href="/point-of-purchase-displays">Point Of Purchase Displays</a></li>
                    </ul>
                </div>
            </div>
            <div>
                <div class="font-semibold pb-4">
                    <h2>Services</h2>
                </div>
                <div class="text-sm">
                    <ul>
                        <li><a href="/pump-repair-services">Pump Repair Services</a></li>
                        <li><a href="/machinery-rebuilders">Machinery Rebuilders</a></li>
                        <li><a href="/boiler-renting">Boiler Renting</a></li>
                        <li><a href="/spindle-rebuilding-repairing">Spindle Rebuilding & Repairing</a></li>
                        <li><a href="/advertising-novelties-specialties">Advertising Novelties & Specialties</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <section class="bg-gradient-to-r from-[#092C47] to-[#21435E] py-16 px-6">
        <div class="max-w-7xl mx-auto flex flex-col gap-10">
            
                <!-- Header -->
                <header class="flex flex-col gap-4 text-center">
                <h1 class="text-white text-3xl md:text-4xl">
                    Find Suppliers, Insights, Tools and More...
                </h1>
                <h3 class="text-white text-lg md:text-xl">
                    Become part of North America's largest and most active network of B2B buyers and industrial/commercial suppliers.
                </h3>
                </header>

                <!-- Highlights Grid -->
                <ul class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                
                <!-- Item 1 -->
                <li class="relative group">
                    <a class="flex flex-col items-center gap-5">
                    <svg viewBox="0 0 32 32" class="text-white p-4 shadow-[0_2px_4px_rgba(0,0,0,0.5)] rounded-full  w-20 h-20">
                        <path d="M18.354 5.9l4.83-1.294a1 1 0 0 1 1.224.707l3.624 13.523a1 1 0 0 1-.707 1.225l-8.774 2.35a7.35 7.35 0 0 0-7.075-3.542L8.953 9.454A1 1 0 0 1 9.66 8.23l4.83-1.295 1.035 3.864 3.864-1.035L18.354 5.9zM6.905 21.13L3.347 7.85l-1.932.517a1 1 0 0 1-1.224-.707l-.26-.966A1 1 0 0 1 .64 5.47l1.932-.517 1.932-.518a1 1 0 0 1 1.224.707L9.534 19.35a7.33 7.33 0 0 0-2.629 1.78zM19.34 24.27l9.47-2.537a1 1 0 0 1 1.224.707l.259.966a1 1 0 0 1-.707 1.225l-10.088 2.703a7.306 7.306 0 0 0-.158-3.064zm-9.566-3.11a5.59 5.59 0 1 1 4.942 10.028 5.59 5.59 0 0 1-4.942-10.027zm3.41 6.921a2.128 2.128 0 0 0 .968-2.846 2.128 2.128 0 0 0-2.847-.967 2.128 2.128 0 0 0-.967 2.846 2.128 2.128 0 0 0 2.846.967z" fill="currentColor"/>
                    </svg>
                    <h3 class="text-white text-center text-sm font-medium">
                        Select From Over 500,000 <br> Industrial Suppliers
                    </h3>
                    </a>
                    <a class="absolute inset-0 opacity-0 group-hover:opacity-100 shadow-[0_2px_4px_rgba(0,0,0,0.5)] bg-[#21435E] flex items-center justify-center text-white text-sm px-6 py-4 transition">
                    Find and evaluate OEMs, Custom Manufacturers, Service Companies and Distributors.
                    </a>
                </li>

                <!-- Item 2 -->
                <li class="relative group">
                    <a class="flex flex-col items-center gap-5">
                    <svg viewBox="0 0 32 32" class="shadow-[0_2px_4px_rgba(0,0,0,0.5)] text-white p-4 rounded-full w-20 h-20">
                        <path d="M24.026 10.301c.267.26.497.614.688 1.06.19.447.286.856.286 1.228v16.072c0 .372-.134.688-.401.948s-.592.391-.974.391H4.375c-.382 0-.707-.13-.974-.39A1.275 1.275 0 0 1 3 28.66V6.34c0-.373.134-.689.401-.95.267-.26.592-.39.974-.39h12.833c.382 0 .802.093 1.26.279.46.186.822.41 1.09.67l4.468 4.352zM6 9v6h6V9H6zm0 8v2h16v-2H6zm0 4v2h16v-2H6zm0 4v2h16v-2H6zm11.807-12.616V8.61a3.517 3.517 0 0 0-2.032.96 3.307 3.307 0 0 0 0 4.783c.681.66 1.575.99 2.468.99.894 0 1.787-.33 2.468-.99.57-.553.899-1.25.992-1.969h-3.896zm1.24-1.151h2.49a2.507 2.507 0 0 0-2.491-2.49v2.49z" fill="currentColor"/>
                    </svg>
                    <h3 class="text-white text-center text-sm font-medium">
                        Receive Daily <br> Industry Updates
                    </h3>
                    </a>
                    <a class="absolute inset-0 opacity-0 group-hover:opacity-100 shadow-[0_2px_4px_rgba(0,0,0,0.5)] bg-[#21435E] flex items-center justify-center text-white text-sm px-6 py-4 transition">
                    Stay up to date on industry news and trends, product announcements and the latest innovations.
                    </a>
                </li>

                <!-- You can copy same pattern for Item 3 + Item 4 with their respective SVGs -->
                <li class="relative group">
                    <a class="flex flex-col items-center gap-5">
                    <svg viewBox="0 0 32 32" class="shadow-[0_2px_4px_rgba(0,0,0,0.5)] text-white p-4 rounded-full w-20 h-20">
                    <path d="M29.63 19.282a2.25 2.25 0 0 0-.644-1.852L15.99 4.433a4.006 4.006 0 0 0-2.882-1.191l-6.45.06a3.375 3.375 0 0 0-.515.045A3.21 3.21 0 0 1 8.93 1.69l6.228-.059a3.868 3.868 0 0 1 2.783 1.15l12.548 12.55a2.198 2.198 0 0 1-.018 3.11l-.84.84z" fill="white" data-sentry-element="path" data-sentry-source-file="Icons.tsx"></path><path d="M5.843 8.034a1.541 1.541 0 0 1 2.18-.017 1.54 1.54 0 0 1-.018 2.179c-.607.606-1.583.614-2.18.018a1.542 1.542 0 0 1 .018-2.18M3.561 5.752a3.21 3.21 0 0 1 2.248-.941l6.227-.059a3.868 3.868 0 0 1 2.783 1.15l12.55 12.55a2.198 2.198 0 0 1-.02 3.11l-7.986 7.987a2.197 2.197 0 0 1-3.11.018L3.704 17.018a3.868 3.868 0 0 1-1.15-2.783l.059-6.228c.008-.875.37-1.676.948-2.255zM18.876 13.4l-1.148.77a4.796 4.796 0 0 0-.787-.314l-.235-1.368a.302.302 0 0 0-.283-.22h-1.384a.302.302 0 0 0-.283.22l-.252 1.352a3.35 3.35 0 0 0-.77.33l-1.148-.801c-.095-.063-.268-.048-.363.047l-.959.959c-.094.094-.11.268-.047.362l.786 1.132a5.336 5.336 0 0 0-.345.818l-1.369.267c-.125 0-.236.142-.236.268l.016 1.368c0 .126.094.252.22.283l1.384.252c.063.283.189.534.315.786l-.771 1.148c-.078.11-.078.267.016.361l.975.975c.094.095.251.095.361.016l1.149-.77c.251.125.519.236.802.33l.251 1.352c.032.126.142.236.268.236h1.384a.27.27 0 0 0 .267-.236l.267-1.368c.267-.078.535-.189.803-.33l1.132.786c.094.064.267.047.346-.031l.975-.975c.094-.094.11-.267.047-.362l-.802-1.147c.125-.253.251-.504.33-.771l1.337-.268a.27.27 0 0 0 .236-.267l-.016-1.368c.015-.142-.094-.252-.22-.283l-1.353-.252a4.798 4.798 0 0 0-.314-.786l.77-1.148a.264.264 0 0 0-.031-.346l-.975-.975a.264.264 0 0 0-.346-.032z" fill="white" data-sentry-element="path" data-sentry-source-file="Icons.tsx"></path><path d="M17.382 19.58a2.373 2.373 0 0 1-3.334 0 2.359 2.359 0 0 1 0-3.333 2.345 2.345 0 0 1 3.334 0 2.358 2.358 0 0 1 0 3.334" fill="white" data-sentry-element="path" data-sentry-source-file="Icons.tsx"></path>
                    </svg>
                    <h3 class="text-white text-center text-sm font-medium">
                        Receive Daily <br> Industry Updates
                    </h3>
                    </a>
                    <a class="absolute inset-0 opacity-0 group-hover:opacity-100 bg-[#21435E] shadow-[0_2px_4px_rgba(0,0,0,0.5)] flex items-center justify-center text-white text-sm px-6 py-4 transition">
                    Stay up to date on industry news and trends, product announcements and the latest innovations.
                    </a>
                </li>
                <li class="relative group">
                    <a class="flex flex-col items-center gap-5">
                    <svg viewBox="0 0 32 32" class="shadow-[0_2px_4px_rgba(0,0,0,0.5)] text-white p-4 rounded-full w-20 h-20">
                    <g fill="currentColor" fill-rule="evenodd" data-sentry-element="g" data-sentry-source-file="Icons.tsx"><path d="M16.553 2.22L28.5 7.512 23.03 10.22l-6.668-2.66a.482.482 0 0 0-.413-.003l-6.59 2.663-5.6-2.708 11.985-5.293a1 1 0 0 1 .81 0zM8.638 21.82a.492.492 0 0 0 .114.163.79.79 0 0 0 .098.068l5.895 2.65v5.445l-11.09-5.283a1 1 0 0 1-.57-.903V9.432l5.51 2.778v9.427c.014.1.025.142.043.182z" data-sentry-element="path" data-sentry-source-file="Icons.tsx"></path><path d="M15.094 17.1l-4.433 2.15v-6.775l4.433-2.149zM16.136 18.86l4.603 2.068-4.603 2.23-4.603-2.23z" data-sentry-element="path" data-sentry-source-file="Icons.tsx"></path><path d="M28.71 24.892l-11.203 5.284V24.73l6.012-2.65a.52.52 0 0 0 .194-.19.567.567 0 0 0 .052-.168c.004-.03.006-3.19.008-9.483l5.51-2.777v14.525a1 1 0 0 1-.573.905z" data-sentry-element="path" data-sentry-source-file="Icons.tsx"></path><path d="M21.605 12.475v6.774l-4.434-2.148v-6.775z" data-sentry-element="path" data-sentry-source-file="Icons.tsx"></path></g>
                    </svg>
                    <h3 class="text-white text-center text-sm font-medium">
                        Receive Daily <br> Industry Updates
                    </h3>
                    </a>
                    <a class="absolute inset-0 opacity-0 group-hover:opacity-100 bg-[#21435E] shadow-[0_2px_4px_rgba(0,0,0,0.5)] flex items-center justify-center text-white text-sm px-6 py-4 transition">
                    Stay up to date on industry news and trends, product announcements and the latest innovations.
                    </a>
                </li>

                </ul>

                <!-- Footer -->
                <footer class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button class="bg-[#0D6AED] text-white text-sm px-4 py-2 rounded-sm flex items-center gap-2 hover:bg-blue-600 transition">
                        🔍 Start Sourcing Suppliers
                    </button>
                    <a href="{{ route('company.register') }}" class="border border-white text-white text-sm px-4 py-2 rounded flex items-center gap-2 hover:bg-white hover:text-blue-900 transition">
                        Claim Your Company Profile →
                    </a>
                </footer>
        </div>
    </section>
@endsection