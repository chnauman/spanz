<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Privacy Policy - SPANZ</title>
    @php($legalCssQuery = is_file(public_path('css/output.css')) ? filemtime(public_path('css/output.css')) : time())
    <link rel="stylesheet" href="{{ asset('css/output.css') }}?v={{ $legalCssQuery }}">
    <style>
        :root {
            --thomas-navy: #032747;
            --thomas-blue: #0d6efd;
            --thomas-bg: #f3f6fa;
            --thomas-border: #d8e2ee;
            --thomas-text: #15314c;
        }

        html, body { height: 100%; margin: 0; padding: 0; }

        body.spanz-legal {
            background: var(--thomas-bg);
            color: var(--thomas-text);
            font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            font-size: 16px;
            line-height: 1.65;
        }

        .thomas-topbar {
            background: linear-gradient(180deg, #032747 0%, #0a3255 100%);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            padding-top: 8px;
            padding-bottom: 8px;
        }

        .thomas-nav-link {
            font-size: 1.05rem;
            font-weight: 700;
            color: #e6eef7;
        }

        .thomas-nav-link:hover { color: #9ec5ff; }

        /* Header action buttons */
        .nav-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            line-height: 1;
            letter-spacing: 0.01em;
            transition: background-color .18s ease, color .18s ease, border-color .18s ease, transform .18s ease, box-shadow .18s ease;
            text-decoration: none;
            white-space: nowrap;
        }
        .nav-btn svg { flex-shrink: 0; }
        .nav-btn:hover { transform: translateY(-1px); }

        .nav-btn-primary {
            background: #0D6AED;
            color: #fff;
            border: 1px solid #0D6AED;
            box-shadow: 0 4px 12px rgba(13, 106, 237, 0.28);
        }
        .nav-btn-primary:hover {
            background: #0B5AC7;
            border-color: #0B5AC7;
            color: #fff;
            box-shadow: 0 6px 16px rgba(13, 106, 237, 0.36);
        }

        .nav-btn-outline {
            background: transparent;
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.55);
        }
        .nav-btn-outline:hover {
            background: #fff;
            color: #092C47;
            border-color: #fff;
        }

        @media (max-width: 480px) {
            .nav-btn { padding: 7px 12px; font-size: 0.82rem; }
            .nav-btn svg { width: 14px; height: 14px; }
        }

        .legal-hero {
            background: linear-gradient(180deg, #092C47 0%, #0c3a5d 100%);
            color: #fff;
        }

        .legal-hero .eyebrow {
            display: inline-flex;
            align-items: center;
            background: rgba(255,255,255,.12);
            border: 1px solid rgba(255,255,255,.22);
            color: #fff;
            font-size: 11px;
            letter-spacing: .12em;
            text-transform: uppercase;
            font-weight: 700;
            padding: 6px 14px;
            border-radius: 999px;
        }

        .legal-hero h1 {
            font-family: Georgia, 'Times New Roman', serif;
            font-weight: 600;
            letter-spacing: -0.01em;
            line-height: 1.15;
        }

        .legal-card {
            background: #fff;
            border: 1px solid var(--thomas-border);
            border-radius: 14px;
            box-shadow: 0 6px 18px rgba(3, 39, 71, 0.06);
        }

        .legal-content h2 {
            font-family: Georgia, 'Times New Roman', serif;
            color: #092C47;
            font-weight: 600;
            font-size: clamp(1.25rem, 1.1vw + 0.85rem, 1.6rem);
            margin-top: 2.25rem;
            margin-bottom: .75rem;
            padding-bottom: .5rem;
            border-bottom: 1px solid #e4ecf5;
            scroll-margin-top: 90px;
        }

        .legal-content h3 {
            font-family: Georgia, 'Times New Roman', serif;
            color: #0d3a5e;
            font-weight: 600;
            font-size: 1.1rem;
            margin-top: 1.4rem;
            margin-bottom: .5rem;
        }

        .legal-content p { margin-bottom: 1rem; color: #2a4a68; }

        .legal-content ul {
            list-style: disc;
            padding-left: 1.35rem;
            margin-bottom: 1rem;
            color: #2a4a68;
        }

        .legal-content ul li { margin-bottom: .4rem; }

        .legal-content strong { color: #092C47; }

        .legal-content a { color: #0D6AED; text-decoration: underline; }
        .legal-content a:hover { color: #0b54bd; }

        .toc {
            position: sticky;
            top: 90px;
            background: #fff;
            border: 1px solid var(--thomas-border);
            border-radius: 12px;
            padding: 16px 18px;
            max-height: calc(100vh - 110px);
            overflow-y: auto;
            box-shadow: 0 4px 14px rgba(3, 39, 71, 0.04);
        }

        .toc h4 {
            font-family: Georgia, 'Times New Roman', serif;
            color: #092C47;
            font-size: .82rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .1em;
            margin-bottom: .75rem;
        }

        .toc ol {
            list-style: none;
            padding: 0;
            margin: 0;
            counter-reset: section;
        }

        .toc ol li {
            counter-increment: section;
            padding: 4px 0;
            font-size: .88rem;
            line-height: 1.4;
        }

        .toc ol li a {
            color: #21435E;
            text-decoration: none;
            display: block;
            padding: 4px 6px;
            border-radius: 6px;
            transition: background .15s ease, color .15s ease;
        }

        .toc ol li a::before {
            content: counter(section) ". ";
            color: #0D6AED;
            font-weight: 700;
            margin-right: 4px;
        }

        .toc ol li a:hover { background: #eaf2ff; color: #0D6AED; }

        .effective-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #ECF4FF;
            color: #092C48;
            border: 1px solid #c8dcf5;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: .8rem;
            font-weight: 600;
        }

        .back-to-top {
            position: fixed;
            right: 22px;
            bottom: 22px;
            background: #092C47;
            color: #fff;
            border: 0;
            border-radius: 999px;
            width: 44px;
            height: 44px;
            display: none;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 22px rgba(9,44,71,.28);
            cursor: pointer;
            z-index: 60;
        }

        .back-to-top.visible { display: inline-flex; }
        .back-to-top:hover { background: #0d3a5e; }

        .info-box {
            background: linear-gradient(180deg, #F5F9FF 0%, #ECF4FF 100%);
            border: 1px solid #c8dcf5;
            border-left: 4px solid #0D6AED;
            border-radius: 10px;
            padding: 14px 18px;
            margin: 1rem 0;
            color: #21435E;
        }

        @media print {
            .thomas-topbar, .toc, .back-to-top, footer { display: none !important; }
            .legal-card { box-shadow: none; border: 0; }
        }
    </style>
</head>

<body class="spanz-legal">
    <!-- Navbar -->
    <div class="thomas-topbar py-5">
        <nav>
            <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-14">
                    <div class="flex-shrink-0">
                        <a href="{{ route('home') }}" class="block text-white text-3xl font-extrabold italic tracking-widest leading-none"
                           style="font-family: 'Eurostile', 'Orbitron', 'Arial Black', sans-serif;">
                            SPANZ
                        </a>
                    </div>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('home') }}" class="nav-btn nav-btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M3 10.5 12 3l9 7.5"/>
                                <path d="M5 9.5V21h14V9.5"/>
                                <path d="M10 21v-6h4v6"/>
                            </svg>
                            <span>Home</span>
                        </a>
                        @auth
                            <a href="{{ route('dashboard') }}" class="nav-btn nav-btn-outline">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <rect x="3" y="3" width="7" height="9" rx="1.5"/>
                                    <rect x="14" y="3" width="7" height="5" rx="1.5"/>
                                    <rect x="14" y="12" width="7" height="9" rx="1.5"/>
                                    <rect x="3" y="16" width="7" height="5" rx="1.5"/>
                                </svg>
                                <span>Dashboard</span>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="nav-btn nav-btn-outline">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                                    <polyline points="10 17 15 12 10 7"/>
                                    <line x1="15" y1="12" x2="3" y2="12"/>
                                </svg>
                                <span>Login</span>
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>
    </div>

    <!-- Hero -->
    <section class="legal-hero">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16 text-center">
            <span class="eyebrow">SPANZ&reg; Legal</span>
            <h1 class="mt-5 text-3xl sm:text-4xl lg:text-5xl">Privacy Policy</h1>
            <p class="mt-4 text-sm sm:text-base text-white/80 max-w-3xl mx-auto">
                Your privacy matters to us. This policy explains how SPANZ&reg; Pty Ltd collects, uses, discloses and stores your personal information in accordance with the Privacy Act 1988 (Cth) and the Australian Privacy Principles (APPs).
            </p>
            <div class="mt-6">
                <span class="effective-pill">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    Last updated: July 2026
                </span>
            </div>
        </div>
    </section>

    <!-- Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- TOC -->
            <aside class="hidden lg:block lg:col-span-3">
                <div class="toc">
                    <h4>On this page</h4>
                    <ol>
                        <li><a href="#overview">Overview</a></li>
                        <li><a href="#consent">Consent</a></li>
                        <li><a href="#collect">Information We Collect</a></li>
                        <li><a href="#why">Why We Collect It</a></li>
                        <li><a href="#how">How We Collect It</a></li>
                        <li><a href="#disclose">Disclosing Information</a></li>
                        <li><a href="#marketing">Direct Marketing</a></li>
                        <li><a href="#security">Security</a></li>
                        <li><a href="#access">Access &amp; Correction</a></li>
                        <li><a href="#destroy">Destroying / De-identifying</a></li>
                        <li><a href="#complaints">Complaints</a></li>
                        <li><a href="#changes">Changes</a></li>
                        <li><a href="#contact">Contact Us</a></li>
                    </ol>
                </div>
            </aside>

            <article class="lg:col-span-9 legal-content">
                <div class="legal-card p-6 sm:p-10">
                    <h2 id="overview">1. Overview</h2>
                    <p>
                        Thank you for using SPANZ.com.au platform, it is operated by <strong>SPANZ&reg; Pty Ltd</strong> duly registered in Australia. Your privacy is important to us and we are committed to protecting your privacy in accordance with the <strong>Privacy Act 1988 (Cth)</strong> (Privacy Act), which includes the Australian Privacy Principles (APPs) and any related privacy codes.
                    </p>
                    <p>
                        This Policy outlines how we collect, use, disclose and store your personal information and lets you know how you can access that information. This Policy applies to our obligations when handling information in Australia. Please read this Policy carefully and contact us using the details below if you have questions.
                    </p>

                    <h2 id="consent">2. Consent</h2>
                    <p>
                        By providing personal information, you consent to us collecting, using, storing and disclosing your personal information in accordance with this Policy or as required or permitted by law. If you continue using our services, then we will treat your use as your consent to us handling your personal information in accordance with this Policy.
                    </p>

                    <h2 id="collect">3. What kinds of personal information do we collect and hold?</h2>
                    <p>The type of personal information we collect depends on the circumstances of its collection and the nature of your dealings with us.</p>

                    <h3>If you are an employee or contractor of a business that has a user profile on SPANZ, this information may include:</h3>
                    <ul>
                        <li>Your name;</li>
                        <li>Your contact information, such as your email address and mobile number;</li>
                        <li>Employment information, such as your place of employment, job title, employment contact details, skill sets and resume;</li>
                        <li>Your device ID, device type and information, geo-location information, Internet Protocol (IP) address and standard web log information; and</li>
                        <li>Information contained in any communications between you and us.</li>
                    </ul>

                    <h3>If you are a prospective employee or independent contractor applying to work with SPANZ, we may collect:</h3>
                    <ul>
                        <li>Your name, address and contact details;</li>
                        <li>Business registration details, such as your Australian Business Number;</li>
                        <li>Your employment details and qualifications;</li>
                        <li>Billing and payment information; or</li>
                        <li>Information you provide to us as part of the recruitment process.</li>
                    </ul>

                    <h3>For general users who may not have subscribed to our service but interact with us:</h3>
                    <p>We may collect information when you connect with us or use our website. This may include information:</p>
                    <ul>
                        <li>Provided in communications we have with you; and</li>
                        <li>About your access and use of our website, including browser session data, device and network information, statistics on page views, acquisition sources, search queries, browsing behaviour and information gathered through internet cookies.</li>
                    </ul>

                    <div class="info-box">
                        If you choose not to provide information as requested, we may not be able to service your needs. For example, it will not be possible for us to provide you with our service if you want to remain anonymous or use a pseudonym.
                    </div>

                    <p>
                        We sometimes receive unsolicited personal information. In circumstances where we receive unsolicited personal information we will usually destroy or de-identify the information as soon as practicable if it is lawful and reasonable to do so, unless the unsolicited personal information is reasonably necessary for, or directly related to, our functions or activities.
                    </p>

                    <h2 id="why">4. Why do we collect your personal information?</h2>
                    <p>We collect your personal information primarily to provide our service of facilitating and progressing client and provider matches via SPANZ.</p>
                    <p>Some ways we use your personal information are:</p>
                    <ul>
                        <li>For the purpose for which the personal information was originally collected, including matching companies to opportunities based on their available staff&rsquo;s qualifications, skill sets and experience;</li>
                        <li>To identify and interact with you;</li>
                        <li>To perform administrative and operational functions;</li>
                        <li>To comply with any legal requirements, including any purpose authorised or required by an Australian law, court or tribunal; and</li>
                        <li>For any other purpose for which you give your consent.</li>
                    </ul>

                    <p>In relation to the personal information of independent contractors or prospective staff members seeking employment at SPANZ, we collect personal information to allow us to:</p>
                    <ul>
                        <li>Carry out our recruitment functions;</li>
                        <li>Correspond with you;</li>
                        <li>Fulfil the terms of any contractual relationship; and</li>
                        <li>Ensure that you can perform your duties.</li>
                    </ul>

                    <h2 id="how">5. How we collect your personal information</h2>
                    <h3>You give it to us</h3>
                    <p>We collect personal information directly from you when you:</p>
                    <ul>
                        <li>Use our services;</li>
                        <li>Interact or share personal information with us via the SPANZ website and social media; and</li>
                        <li>Communicate with us.</li>
                    </ul>

                    <h3>We collect it</h3>
                    <p>We may also collect your personal information from third parties including:</p>
                    <ul>
                        <li>Your employer;</li>
                        <li>Service providers;</li>
                        <li>Referrals who may have referred you to us; and</li>
                        <li>Organisations with whom we have an agreement to share information with.</li>
                    </ul>

                    <p>
                        We will generally obtain consent from the owner of personal information to collect their personal information. Consent will usually be provided in writing; however, sometimes it may be provided orally or may be implied through a person&rsquo;s conduct. We endeavour to only ask for your personal information if it is reasonably necessary for the activities that you are seeking to be involved in.
                    </p>

                    <h2 id="disclose">6. Disclosing your personal information</h2>
                    <p>We may disclose your personal information to the following third parties:</p>
                    <ul>
                        <li>To other businesses that use our service, if you may have the potential to be placed within that organisation;</li>
                        <li>To our business or commercial partners;</li>
                        <li>To our professional advisers and agents;</li>
                        <li>Third parties and contractors who provide services to us, including customer enquiries and support services, IT service providers, data storage, web-hosting and server providers, payment processing service providers;</li>
                        <li>Payment system operators and debt-recovery functions;</li>
                        <li>Third parties to collect and process data, such as Microsoft Azure; and</li>
                        <li>Any third parties authorised by you to receive information held by us.</li>
                    </ul>
                    <p>We may also disclose your personal information if we are required, authorised or permitted by law. <strong>We do not send information to third parties that are located outside of Australia.</strong></p>

                    <h2 id="marketing">7. Using your personal information for direct marketing</h2>
                    <p>
                        From time to time, and in support of our future development and growth, we may use your personal information to contact you to promote and market our products and services. You can opt-out from being contacted for direct marketing purposes by contacting us at <a href="mailto:privacy@spanz.com.au">privacy@spanz.com.au</a> or by using the unsubscribe facility included in each direct marketing communication we send. Once we receive a request to opt out from receiving marketing information, we will stop sending such information within a reasonable amount of time.
                    </p>

                    <h2 id="security">8. Security</h2>
                    <p>
                        We take all reasonable steps to protect personal information under our control from misuse, interference and loss and from unauthorised access, modification or disclosure. We hold your personal information electronically and onshore in secure databases operated by our third-party service providers.
                    </p>
                    <p>
                        We protect the personal information we hold through a collection of risk-mitigating measures including an enterprise-grade web application firewall, data encrypted during transport and at rest, network monitoring artificial intelligence within the datacenter, and password encryption with salted SHA-256 encryption. While we take reasonable steps to ensure your personal information is protected from loss, misuse and unauthorised access, modification or disclosure, security measures over the internet can never be guaranteed.
                    </p>
                    <p>
                        We encourage you to play an important role in keeping your personal information secure, by maintaining the confidentiality of any passwords and account details used on our website.
                    </p>

                    <h2 id="access">9. Accessing or correcting your personal information</h2>
                    <p>
                        If you would like to access the personal information we may hold about you, please contact us using the details below. In certain circumstances, we may not be able to give you access to your personal information in which case we will write to you to explain why we cannot comply with your request.
                    </p>
                    <p>
                        We try to ensure any personal information we hold about you is accurate, up-to-date, complete and relevant. If you believe the personal information we hold about you should be updated, please contact us using the details below and we will take reasonable steps to ensure it is corrected if appropriate.
                    </p>

                    <h2 id="destroy">10. Destroying or de-identifying personal information</h2>
                    <p>We destroy or de-identify personal information when we no longer need it, unless we are otherwise required or authorised by law to retain the information.</p>

                    <h2 id="complaints">11. Making a complaint</h2>
                    <p>If you believe your privacy has been breached or you have a complaint about our handling of your personal information, please contact us using the details below.</p>
                    <p>
                        We take privacy complaints seriously. If you make a complaint, we aim to respond within <strong>5 business days</strong> to acknowledge your complaint. We will try to resolve your complaint within <strong>30 days</strong>. When this is not reasonably possible, we will contact you within that time to let you know how long it will take to resolve your complaint. We will investigate your complaint and write to you to explain our decision as soon as practicable.
                    </p>
                    <p>
                        If you are not satisfied with our decision, you can refer your complaint to the <strong>Office of the Australian Information Commissioner</strong> by phone on <strong>1300 363 992</strong> or online at <a href="https://www.oaic.gov.au" target="_blank" rel="noopener">www.oaic.gov.au</a>.
                    </p>

                    <h2 id="changes">12. Changes</h2>
                    <p>We may, from time to time, amend this Policy. Any changes to this Policy will be effective immediately upon the posting of the revised Policy on our website. By continuing to use the services following any changes, you will be deemed to have agreed to such changes.</p>

                    <h2 id="contact">13. Contact us</h2>
                    <p>All questions or queries about this Policy and complaints should be directed via the contact information as provided on <a href="{{ route('home') }}">spanz.com.au</a>.</p>

                    <div class="info-box">
                        <strong>Privacy enquiries:</strong> <a href="mailto:privacy@spanz.com.au">privacy@spanz.com.au</a><br>
                        <strong>General enquiries:</strong> <a href="mailto:enquiries@spanz.com.au">enquiries@spanz.com.au</a>
                    </div>

                    <p class="text-sm text-gray-500 mt-8">This Policy was last updated in July 2026.</p>

                    <div class="mt-6 rounded-xl border border-[#0D6AED]/20 bg-gradient-to-r from-[#F5F9FF] to-[#ECF4FF] p-5">
                        <p class="text-sm text-[#21435E] m-0">
                            For the legal framework that governs your use of the platform, please also read our
                            <a href="{{ route('terms') }}" target="_blank" rel="noopener">Terms &amp; Conditions</a>.
                        </p>
                    </div>
                </div>
            </article>
        </div>
    </main>

    <button id="backToTop" class="back-to-top" aria-label="Back to top" title="Back to top">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"></polyline></svg>
    </button>

    @include('components.mainfooter')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const backToTop = document.getElementById('backToTop');
            if (backToTop) {
                window.addEventListener('scroll', function () {
                    if (window.scrollY > 320) {
                        backToTop.classList.add('visible');
                    } else {
                        backToTop.classList.remove('visible');
                    }
                });
                backToTop.addEventListener('click', function () {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
            }
        });
    </script>
</body>
</html>
