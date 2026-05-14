<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Terms &amp; Conditions - SPANZ</title>
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

        .dropdown-group { position: relative; }

        .dropdown-menu {
            position: absolute;
            left: 0;
            top: 100%;
            margin-top: 0.5rem;
            background: white;
            border-radius: 0.375rem;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,.1), 0 4px 6px -2px rgba(0,0,0,.05);
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transform: translateY(-10px);
            transition: all .25s ease-in-out;
            z-index: 60;
            min-width: 14rem;
        }

        .dropdown-group:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
            transform: translateY(0);
        }

        /* Hero band */
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

        /* Content card */
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

        /* Layout: explicit grid so the TOC column stays readable if Tailwind grid utilities are missing on deploy */
        .legal-page-grid {
            display: grid;
            gap: 2rem;
            grid-template-columns: 1fr;
            align-items: start;
        }

        .legal-page-toc { display: none; }

        .legal-page-body { min-width: 0; }

        @media (min-width: 1024px) {
            .legal-page-grid {
                grid-template-columns: minmax(260px, 22rem) minmax(0, 1fr);
            }

            .legal-page-toc { display: block; }
        }

        /* Sticky TOC */
        .toc {
            position: sticky;
            top: 90px;
            background: #fff;
            border: 1px solid var(--thomas-border);
            border-radius: 12px;
            padding: 16px 18px;
            max-height: none;
            overflow-y: visible;
            box-shadow: 0 4px 14px rgba(3, 39, 71, 0.04);
            width: 100%;
            box-sizing: border-box;
        }

        .toc h4 {
            font-family: Georgia, 'Times New Roman', serif;
            color: #092C47;
            font-size: .82rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .1em;
            margin-bottom: .75rem;
            line-height: 1.3;
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
            overflow-wrap: break-word;
        }

        .toc ol li a::before {
            content: counter(section) ". ";
            color: #0D6AED;
            font-weight: 700;
            margin-right: 4px;
        }

        .toc ol li a:hover { background: #eaf2ff; color: #0D6AED; }

        /* Definition list look */
        .term-list { margin: 0 0 1rem 0; }
        .term-list > div { padding: 8px 0; border-bottom: 1px dashed #e4ecf5; }
        .term-list > div:last-child { border-bottom: 0; }
        .term-list dt { font-weight: 700; color: #092C47; display: inline; }
        .term-list dd { display: inline; margin-left: 6px; color: #2a4a68; }

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

        @media print {
            .thomas-topbar, .toc, .legal-page-toc, .back-to-top, footer { display: none !important; }
            .legal-page-grid { grid-template-columns: 1fr !important; }
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
            <h1 class="mt-5 text-3xl sm:text-4xl lg:text-5xl">Terms &amp; Conditions</h1>
            <p class="mt-4 text-sm sm:text-base text-white/80 max-w-3xl mx-auto">
                These Terms govern your access to and use of the SPANZ.com.au online procurement platform. Please read them carefully &mdash; by registering, accessing or using the Portal, you agree to be bound by them.
            </p>
            <div class="mt-6">
                <span class="effective-pill">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    Effective Date: 01 November 2025
                </span>
            </div>
        </div>
    </section>

    <!-- Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14">
        <div class="legal-page-grid">
            <!-- TOC -->
            <aside class="legal-page-toc">
                <div class="toc">
                    <h4>On this page</h4>
                    <ol>
                        <li><a href="#definitions">Definitions</a></li>
                        <li><a href="#registration">Registration &amp; Accounts</a></li>
                        <li><a href="#use">Use of the Portal</a></li>
                        <li><a href="#ip">Intellectual Property</a></li>
                        <li><a href="#fees">Fees &amp; Payments</a></li>
                        <li><a href="#content">Content &amp; Approval</a></li>
                        <li><a href="#confidentiality">Confidentiality &amp; Data Protection</a></li>
                        <li><a href="#disclaimers">Disclaimers &amp; Liability</a></li>
                        <li><a href="#indemnity">Indemnification</a></li>
                        <li><a href="#termination">Termination &amp; Refunds</a></li>
                        <li><a href="#interpretation">Interpretation</a></li>
                        <li><a href="#disputes">Dispute Resolution</a></li>
                        <li><a href="#law">Governing Law</a></li>
                        <li><a href="#amendments">Amendments</a></li>
                        <li><a href="#privacy">Privacy Policy (Annex A)</a></li>
                    </ol>
                </div>
            </aside>

            <article class="legal-page-body legal-content">
                <div class="legal-card p-6 sm:p-10">
                    <p>
                        This Agreement (<strong>&ldquo;Terms&rdquo;</strong>) govern your access to and use of the SPANZ.com.au online platform (<strong>&ldquo;Portal&rdquo;</strong>). By registering, accessing, or using the Portal, you agree to these Terms. If you do not agree, you may not use the Portal.
                    </p>

                    <h2 id="definitions">1. Definitions</h2>
                    <dl class="term-list">
                        <div><dt>&ldquo;Agreement&rdquo;</dt><dd>means these Terms and Conditions and its attachments and annexes.</dd></div>
                        <div><dt>&ldquo;Portal&rdquo;</dt><dd>means the online procurement platform SPANZ.com.au provided by the Provider.</dd></div>
                        <div><dt>&ldquo;User&rdquo; / &ldquo;You&rdquo;</dt><dd>means any entity, registered business, its employees, agents, directors or individual using this portal or authorised to use the Portal.</dd></div>
                        <div><dt>&ldquo;Account&rdquo;</dt><dd>means User&rsquo;s registered account within the Portal.</dd></div>
                        <div><dt>&ldquo;Provider&rdquo;</dt><dd>means the legal owners of the online portal SPANZ.com.au.</dd></div>
                        <div><dt>&ldquo;Buyer&rdquo;</dt><dd>means a registered business posting procurement requirements (i.e. RFXs, Tenders).</dd></div>
                        <div><dt>&ldquo;Supplier&rdquo;</dt><dd>means a registered entity, business agent, director or individual submitting responses to the Buyers.</dd></div>
                        <div><dt>&ldquo;Content&rdquo;</dt><dd>means all information, postings, bids, documents, or data submitted through the Portal or otherwise provided to SPANZ in relation to use of this Portal.</dd></div>
                        <div><dt>&ldquo;Parties&rdquo;</dt><dd>mean the User and the Provider.</dd></div>
                        <div><dt>&ldquo;Privacy Policy&rdquo;</dt><dd>means Annex A to this Agreement.</dd></div>
                    </dl>

                    <h2 id="registration">2. Registration and Accounts</h2>
                    <ul>
                        <li>Users must provide accurate business details and maintain updated information.</li>
                        <li>Accounts are non-transferable and must be kept secure.</li>
                        <li>You are responsible for all activities under your account.</li>
                        <li>We reserve the right to suspend or terminate any account for violation of these Terms or misuse of the Portal.</li>
                        <li>Each Account is for the registered entity only and cannot be shared or transferred without prior written consent from the Provider.</li>
                        <li>Users are responsible for maintaining confidentiality of login credentials and all activities under their account.</li>
                    </ul>

                    <h2 id="use">3. Use of the Portal</h2>
                    <p>The Portal is provided solely for business-to-business communication and acts only as an intermediary platform connecting Users.</p>
                    <ul>
                        <li>The Provider is not responsible for vetting Users, enforcing contracts, or mediating payments disputes beyond any specific procedures outlined elsewhere in the Terms.</li>
                        <li>The Provider does not guarantee uninterrupted access, error-free operation, or specific outcomes from use of the Portal.</li>
                    </ul>

                    <h3>Users must not misuse the Portal, including:</h3>
                    <ul>
                        <li>Uploading false, misleading, or unlawful information.</li>
                        <li>Attempting to gain unauthorised access to the system.</li>
                        <li>Engaging in spam, fraud, or harmful activities.</li>
                    </ul>

                    <ul>
                        <li>The Provider may, without written notice, block, inactivate, suspend, or terminate any Account that violates these Terms.</li>
                        <li>The Provider may, without written notice, block, inactivate, suspend, or terminate any Account at its sole discretion and convenience.</li>
                        <li>The Provider is not a party to any contract formed between the Users.</li>
                        <li>The Provider does not guarantee the quality, legality, accuracy, or performance of any User, nor does it endorse any transaction.</li>
                        <li>The Provider may update these Terms from time to time.</li>
                        <li>Continued use of the Portal after changes are published constitutes acceptance of the updated Terms.</li>
                        <li>All transactions, contracts, and payments are solely between the Users.</li>
                    </ul>

                    <h2 id="ip">4. Intellectual Property</h2>
                    <ul>
                        <li>All intellectual property in the Portal (software, design, branding, and content created by us) remains the Provider&rsquo;s exclusive property.</li>
                        <li>Users retain ownership of their content but grant to the Provider, a worldwide, non-exclusive, royalty-free licence to host, display, and process such content for the purposes of this Portal.</li>
                        <li>Users must not copy, reverse-engineer, or redistribute the Portal without written consent.</li>
                    </ul>

                    <h2 id="fees">5. Fees and Payments</h2>
                    <ul>
                        <li>Access to certain features of the Portal may be subject to subscription or transaction fees and shall be non-refundable unless otherwise stated.</li>
                        <li>Users are responsible for any applicable taxes, domestic or international.</li>
                    </ul>

                    <h2 id="content">6. Content and Intellectual Property</h2>
                    <p>Users retain ownership of their Content but grant the Provider and any of its employees, associates or agents, a non-exclusive, royalty-free license to host, display, and share such Content for the purpose of operating the Portal.</p>

                    <h3>Content and Image Approval Disclaimer</h3>
                    <p><strong>Content Approval and Quality Standards.</strong> All product listings, descriptions, images, logos, documents, and other materials uploaded to or displayed on the SPANZ platform are subject to prior review and approval by SPANZ at its sole discretion.</p>

                    <p>SPANZ reserves the right to assess submitted content for, among other things:</p>
                    <ul>
                        <li>Image quality and resolution</li>
                        <li>Accuracy and completeness of information</li>
                        <li>Professional presentation</li>
                        <li>Relevance to the platform and target audience</li>
                        <li>Compliance with applicable laws and regulations</li>
                        <li>Suitability and consistency with SPANZ&rsquo;s standards, policies, and business objectives</li>
                    </ul>

                    <p>SPANZ may require Members to amend, replace, or remove any content that does not meet these standards. Members agree to promptly make any requested changes within the timeframe specified by SPANZ. If a Member fails or refuses to make the requested changes, SPANZ may, without liability and at its sole discretion:</p>
                    <ul>
                        <li>Reject the content.</li>
                        <li>Decline to publish or display the content.</li>
                        <li>Remove existing content from the platform.</li>
                        <li>Suspend or restrict the relevant listing or account; and/or</li>
                        <li>Terminate the Member&rsquo;s access to the affected services.</li>
                    </ul>

                    <p>Submission of content does not guarantee that it will be approved or published. SPANZ retains absolute discretion over all content displayed on the platform to maintain a high-quality and professional marketplace environment.</p>

                    <ul>
                        <li>Users represent that they have all rights and permissions necessary to post their Content.</li>
                        <li>Users agree that the Portal&rsquo;s software, design, trademarks, and Content remain the property of the Provider and users shall not copy, reproduce, or redistribute it without express written permission from the Provider.</li>
                    </ul>

                    <h2 id="confidentiality">7. Confidentiality and Data Protection</h2>
                    <ul>
                        <li>Users agree to treat information accessed through the Portal (such as tenders, bids, and pricing) as confidential and only use it for legitimate business purposes.</li>
                        <li>The Provider will process personal and business data in accordance with applicable data protection laws and its <a href="{{ route('privacy') }}" target="_blank" rel="noopener">Privacy Policy</a>.</li>
                    </ul>

                    <h2 id="disclaimers">8. Disclaimers and Limitation of Liability</h2>
                    <ul>
                        <li>The Portal is provided <strong>&ldquo;as is&rdquo;</strong> and <strong>&ldquo;as available&rdquo;</strong> without warranties of any kind.</li>
                        <li>The User acknowledges that software will have bugs. A miscalculation, a display error, or a system crash cannot form the basis of a claim against the Provider.</li>
                        <li>The User accepts the Portal with all its existing faults, bugs, and imperfections. The Provider makes no promises that the Portal is fit for any particular purpose, merchantable, secure, or accurate. The User gets what they see, without any guarantees of quality.</li>
                    </ul>

                    <p>To the maximum extent permitted by law, in no event shall the Provider, its affiliates, directors, officers, employees, or agents be liable to the User or any third party for any indirect, incidental, special, consequential, or punitive damages, including without limitation, loss of profits, data, use, goodwill, or other intangible losses, resulting from:</p>
                    <ul>
                        <li>Any losses, financial or otherwise, lost profits, or reputational damages incurred by the User during use of the Portal.</li>
                        <li>The User&rsquo;s access to or use of or inability to access or use the Portal.</li>
                        <li>Any conduct or Content of any third party (including other Users) on the Portal.</li>
                        <li>Any Content obtained from the Portal.</li>
                        <li>Unauthorised access, use, or alteration of the User&rsquo;s Account, transmissions, or Content, whether based on warranty, contract, tort (including negligence), or any other legal theory, whether or not the Provider has been informed of the possibility of such damage.</li>
                    </ul>

                    <h2 id="indemnity">9. User Indemnifies the Provider</h2>
                    <p>The User agrees to indemnify, defend, and hold harmless the Provider, its affiliates, officers, directors, agents, and employees from and against all claims, liabilities, damages, losses, costs, expenses, and fees (including reasonable attorneys&rsquo; fees) arising out of or relating to:</p>
                    <ul>
                        <li>Your access to or use of the Portal, including any data or Content you submit or transmit through the Portal.</li>
                        <li>Your violation of these Terms of Service.</li>
                        <li>Your violation of any third-party right, including without limitation any intellectual property right, privacy right, or confidentiality obligation.</li>
                        <li>Any dispute or issue between you and another User of the Portal.</li>
                        <li>User&rsquo;s violation of any third-party right, including intellectual property.</li>
                        <li>Any claims that the User&rsquo;s Content caused damage to a third party.</li>
                    </ul>
                    <p>The Provider will notify the User promptly of any such claim, suit, or proceeding. The Provider reserves the right to assume the exclusive defense and control of any matter subject to indemnification by the User, at the User&rsquo;s expense, and the User agrees to cooperate with the Provider&rsquo;s defense of these claims. The User will not settle any indemnifiable claim without the Provider&rsquo;s prior written consent, which shall not be unreasonably withheld.</p>

                    <h2 id="termination">10. Termination &amp; Refunds</h2>
                    <ul>
                        <li>Users may deactivate their account at any time by written request.</li>
                        <li>The Provider may suspend, block, inactivate or terminate User&rsquo;s access to the Portal or to User&rsquo;s Account if a User breaches these Terms or misuses the Portal.</li>
                        <li>The Provider may at its sole discretion and convenience suspend, block access to or terminate this Agreement at any time.</li>
                        <li>Termination does not affect any obligations or rights accrued prior to termination.</li>
                        <li>To the maximum extent permitted by applicable law, the Provider&rsquo;s aggregate liability to the User for all claims arising out of or relating to the use of or any inability to use the Portal, whether in contract, tort, or otherwise, is limited to the unused portion of the amount the User had paid to the Provider in the twelve (12) months prior to the event giving rise to the liability.</li>
                    </ul>

                    <h2 id="interpretation">11. Interpretation and Construction</h2>
                    <ul>
                        <li>The User acknowledges and agrees that they have had the opportunity to review this Agreement with independent legal counsel of their own choosing. Consequently, the rule of construction that any ambiguities are to be resolved against the drafting Party shall not apply in the interpretation of this Agreement.</li>
                        <li>The headings and section titles contained in this Agreement are for reference and convenience only and shall not be considered in the interpretation of the substantive provisions of this Agreement.</li>
                        <li>This Agreement constitutes the entire understanding between the Parties concerning its subject matter.</li>
                        <li>The invalidity or unenforceability of any provision of this Agreement shall not affect the validity or enforceability of any other provision. Any provision found to be invalid or unenforceable shall be severed from this Agreement.</li>
                    </ul>

                    <h2 id="disputes">12. Dispute Resolution</h2>
                    <p>The Parties agree that before initiating any formal dispute resolution process, they will first attempt to negotiate in good faith to resolve any dispute, controversy, or claim arising out of or relating to this Agreement, or the breach, termination, or validity thereof (a <strong>&ldquo;Dispute&rdquo;</strong>). The initiating Party shall provide the other Party with written notice of the Dispute, specifying the nature of the Dispute in reasonable detail. The designated representatives of each Party shall meet (virtually or in person) within thirty (30) days of the receipt of such notice to attempt to resolve the Dispute.</p>

                    <p>If the Dispute is not resolved through informal negotiations within sixty (60) days from the date of the initial written notice, the Parties shall endeavour to settle the Dispute by mediation administered by the <strong>Australian Centre for International Commercial Arbitration (ACICA)</strong> or by the <strong>Australian Dispute Centre (ADC)</strong>.</p>

                    <p>The mediation shall be conducted by a single mediator appointed in accordance with the rules of the chosen provider. The Parties will share the costs of the mediation equally, but each Party shall be responsible for its own attorney&rsquo;s fees and other costs associated with the mediation preparation and participation.</p>

                    <p>The mediation shall be held in Melbourne, Victoria, Australia, and the language of the mediation shall be English.</p>

                    <p>The entire mediation process, including any settlement, shall be confidential and treated as a compromise and settlement negotiation. No party may disclose any information regarding the mediation, including the existence of a Dispute, to any third party, except as required by law or to professional advisors under a similar duty of confidentiality. Evidence of anything said or admitted in the mediation and any documents created for the mediation shall not be discoverable or admissible in any subsequent proceedings, except for evidence that is independently discoverable.</p>

                    <p>Participation in mediation is without prejudice to either Party&rsquo;s rights and remedies. The fact that a Dispute is in mediation shall not relieve either Party of its obligations to perform under this Agreement.</p>

                    <p>Nothing in this clause shall prevent either Party from seeking injunctive or other interim relief from a court of competent jurisdiction to prevent irreparable harm or to preserve the status quo while the mediation process is pending. Such a request shall not be deemed a waiver of the obligation to mediate.</p>

                    <p>If the Dispute is not resolved within ninety (90) days after the initiation of the mediation (or such other period as the Parties may agree in writing), the mediation shall be deemed terminated, and either Party may then pursue any and all available remedies, including the right to initiate litigation, subject to the terms of this Agreement.</p>

                    <h2 id="law">13. Governing Law</h2>
                    <ul>
                        <li>These Terms are governed by the laws of the State of Victoria, Australia, without regard to its conflict of laws principles. The parties irrevocably submit to the exclusive jurisdiction of the courts of Victoria, Australia.</li>
                        <li>To the fullest extent permitted by law, the application of the United Nations Convention on Contracts for the International Sale of Goods (CISG) and any other international treaty, convention, protocol, or uniform law that would otherwise apply to this Agreement is expressly excluded.</li>
                    </ul>

                    <h2 id="amendments">14. Amendments</h2>
                    <ul>
                        <li>The Provider may update these Terms from time to time.</li>
                        <li>Continued use of the Portal after changes are posted constitutes acceptance of the revised Terms.</li>
                    </ul>

                    <h2 id="privacy">15. Privacy Policy</h2>
                    <p>SPANZ Pty Ltd operates the SPANZ platform, a service designed to facilitate business-to-business leads and opportunities. We are committed to protecting your privacy in compliance with the Privacy Act 1988 (Cth) and the Australian Privacy Principles (APPs). The full Privacy Policy is available as <strong>Annex A</strong> to this Agreement.</p>
                    <p>
                        Read the full policy here:
                        <a href="{{ route('privacy') }}" target="_blank" rel="noopener">SPANZ Privacy Policy &rarr;</a>
                    </p>

                    <div class="mt-10 rounded-xl border border-[#0D6AED]/20 bg-gradient-to-r from-[#F5F9FF] to-[#ECF4FF] p-5">
                        <p class="text-sm text-[#21435E] m-0">
                            If you have questions about these Terms, please contact us at
                            <a href="mailto:enquiries@spanz.com.au">enquiries@spanz.com.au</a>.
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
