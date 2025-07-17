<?php
session_start();
// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - NQI Sri Lanka</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,700|Sen:400,700,800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #0d47a1;
            --secondary-color: #a13e0d;
            --stakeholder-color: #0d47a1; /* Professional green */
            --stakeholder-light: #468bf1; /* Lighter green */
            --light-color: #f8f9fa;
            --dark-color: #212529;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: #333;
            background-color: #f5f7fa;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .main-content {
            flex: 1;
        }

        .header_section {
            background-color: var(--primary-color);
            padding: 15px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar-light .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.85);
            font-weight: 500;
            padding: 10px 15px;
            transition: all 0.3s;
        }

        .navbar-light .navbar-nav .nav-link:hover,
        .navbar-light .navbar-nav .active>.nav-link {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }

        .navbar-brand {
            font-family: 'Sen', sans-serif;
            font-weight: 800;
            font-size: 1.8rem;
            color: white;
            letter-spacing: 0.5px;
        }

        .banner_section {
            background: linear-gradient(135deg, var(--primary-color), #1a73e8);
            color: white;
            padding: 80px 0 60px;
            position: relative;
            overflow: hidden;
        }

        .banner_section::before {
            content: "";
            position: absolute;
            width: 200%;
            height: 200%;
            top: -50%;
            left: -50%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0) 70%);
            transform: rotate(30deg);
        }

        .banner_section h1 {
            font-family: 'Sen', sans-serif;
            font-weight: 800;
            font-size: 3rem;
            margin-bottom: 20px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .banner_section p {
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto 30px;
            opacity: 0.9;
        }

        .feature_section {
            padding: 80px 0;
        }

        .feature_card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            height: 100%;
            border: none;
        }

        .feature_card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .feature_card .card-header {
            background: linear-gradient(135deg, var(--primary-color), #1a73e8);
            color: white;
            padding: 20px;
            border: none;
        }

        .feature_card .card-body {
            padding: 25px;
        }

        .feature_card .icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: var(--primary-color);
        }

        .feature_card h3 {
            font-family: 'Sen', sans-serif;
            font-weight: 700;
            margin-bottom: 15px;
            color: var(--primary-color);
        }

        .feature_card p {
            color: #666;
        }

        .content_section {
            padding: 80px 0 40px;
            background-color: white;
        }

        .section_title {
            position: relative;
            margin-bottom: 50px;
            text-align: center;
        }

        .section_title h2 {
            font-family: 'Sen', sans-serif;
            font-weight: 800;
            color: var(--primary-color);
            position: relative;
            display: inline-block;
            padding-bottom: 15px;
        }

        .section_title h2::after {
            content: "";
            position: absolute;
            width: 80px;
            height: 4px;
            background: var(--secondary-color);
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 2px;
        }

        .key_areas {
            background-color: #f0f7ff;
            padding: 30px;
            border-radius: 10px;
            margin: 40px 0;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .key_areas h3 {
            color: var(--primary-color);
            margin-bottom: 20px;
            font-weight: 700;
            text-align: center;
        }

        .key_areas ul {
            columns: 2;
            -webkit-columns: 2;
            -moz-columns: 2;
        }

        .key_areas li {
            margin-bottom: 12px;
            position: relative;
            padding-left: 25px;
            font-size: 1.05rem;
        }

        .key_areas li::before {
            content: "\f00c";
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            position: absolute;
            left: 0;
            top: 4px;
            color: var(--secondary-color);
        }

        .partner_logos {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            gap: 30px;
            margin: 40px 0;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .partner_logos .partner-box {
            background: #0033A0;
            color: white;
            padding: 15px 25px;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
            min-width: 150px;
        }

        .partner_logos .eu {
            background: #0057B8;
        }

        .partner_logos .giz {
            background: #154194;
        }

        .footer_section {
            background: linear-gradient(135deg, #0a3a7a, var(--primary-color));
            color: white;
            padding: 60px 0 20px;
        }

        .footer_section h5 {
            font-family: 'Sen', sans-serif;
            font-weight: 700;
            margin-bottom: 25px;
            position: relative;
            padding-bottom: 10px;
        }

        .footer_section h5::after {
            content: "";
            position: absolute;
            width: 40px;
            height: 3px;
            background: var(--secondary-color);
            bottom: 0;
            left: 0;
        }

        .footer_section ul {
            padding-left: 0;
            list-style: none;
        }

        .footer_section ul li {
            margin-bottom: 12px;
        }

        .footer_section ul li a {
            color: rgba(255, 255, 255, 0.8);
            transition: all 0.3s;
            text-decoration: none;
        }

        .footer_section ul li a:hover {
            color: white;
            padding-left: 5px;
        }

        .footer_section .contact_info li {
            display: flex;
            align-items: flex-start;
            margin-bottom: 15px;
        }

        .footer_section .contact_info i {
            margin-right: 15px;
            margin-top: 5px;
            min-width: 20px;
            text-align: center;
        }

        .footer_section .contact_info .fa-map-marker-alt {
            color: #fff;
            background: #ff6f00;
            padding: 5px;
            border-radius: 50%;
            font-size: 14px;
        }

        .footer_section .contact_info .fa-phone {
            color: #fff;
            background: #4CAF50;
            padding: 5px;
            border-radius: 50%;
            font-size: 14px;
        }

        .footer_section .contact_info .fa-envelope {
            color: #fff;
            background: #2196F3;
            padding: 5px;
            border-radius: 50%;
            font-size: 14px;
        }

        .footer_section .contact_info .fa-clock {
            color: #fff;
            background: #9C27B0;
            padding: 5px;
            border-radius: 50%;
            font-size: 14px;
        }

        .social_icon {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .social_icon a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-radius: 50%;
            transition: all 0.3s;
        }

        .social_icon a:hover {
            background: var(--secondary-color);
            transform: translateY(-3px);
        }

        .copyright_section {
            background: rgba(0, 0, 0, 0.2);
            padding: 15px 0;
            font-size: 0.9rem;
            text-align: center;
        }

        .copyright_section p {
            margin: 0;
            color: rgba(255, 255, 255, 0.7);
        }
        
        /* Stakeholder Form Highlight - Green Theme */
        .stakeholder-highlight {
            background: linear-gradient(135deg, var(--stakeholder-light), var(--stakeholder-color));
            padding: 60px 0;
            position: relative;
            overflow: hidden;
            margin: 0;
            color: white;
        }
        
        .stakeholder-highlight::before {
            content: "";
            position: absolute;
            width: 200%;
            height: 200%;
            top: -50%;
            left: -50%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, rgba(255, 255, 255, 0) 70%);
            transform: rotate(30deg);
        }
        
        .stakeholder-highlight .container {
            position: relative;
            z-index: 1;
            max-width: 900px;
        }
        
        .stakeholder-highlight .highlight-card {
            text-align: center;
            padding: 30px;
        }
        
        .stakeholder-highlight h3 {
            font-family: 'Sen', sans-serif;
            font-weight: 700;
            margin-bottom: 20px;
            font-size: 2.2rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        
        .stakeholder-highlight p {
            font-size: 1.15rem;
            margin-bottom: 20px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.7;
        }
        
        .stakeholder-highlight .btn-highlight {
            background: white;
            color: var(--stakeholder-color);
            border: none;
            padding: 14px 40px;
            font-size: 1.1rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            margin-top: 20px;
            display: inline-block;
        }
        
        .stakeholder-highlight .btn-highlight:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            background: #f5f5f5;
        }
        
        .stakeholder-highlight .benefits {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 30px;
            margin-top: 40px;
        }
        
        .stakeholder-highlight .benefit-item {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(5px);
            border-radius: 10px;
            padding: 20px;
            max-width: 250px;
            text-align: center;
            transition: all 0.3s;
        }
        
        .stakeholder-highlight .benefit-item:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.2);
        }
        
        .stakeholder-highlight .benefit-item i {
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: white;
        }
        
        .stakeholder-highlight .benefit-item h4 {
            font-weight: 600;
            margin-bottom: 10px;
            font-size: 1.2rem;
        }
        
        /* Navigation Highlight */
        .nav-item.stakeholder-nav .nav-link {
            background-color: var(--stakeholder-color);
            color: white !important;
            border-radius: 4px;
            font-weight: bold;
            padding: 10px 20px;
        }
        
        .nav-item.stakeholder-nav .nav-link:hover {
            background-color: #1b5e20;
        }

        @media (max-width: 991px) {
            .banner_section h1 {
                font-size: 2.5rem;
            }

            .key_areas ul {
                columns: 1;
            }
        }

        @media (max-width: 767px) {
            .banner_section {
                padding: 60px 0 40px;
            }

            .banner_section h1 {
                font-size: 2rem;
            }

            .feature_section,
            .content_section {
                padding: 60px 0;
            }

            .navbar-brand {
                font-size: 1.5rem;
            }

            .footer_section .row>div {
                margin-bottom: 30px;
            }
            
            .stakeholder-highlight {
                padding: 40px 0;
            }
            
            .stakeholder-highlight .highlight-card {
                padding: 20px;
            }
            
            .stakeholder-highlight h3 {
                font-size: 1.8rem;
            }
            
            .stakeholder-highlight .benefits {
                gap: 15px;
            }
            
            .stakeholder-highlight .benefit-item {
                padding: 15px;
                max-width: 100%;
                flex: 1 1 100%;
            }
        }
    </style>
</head>
<body>
    <div class="header_section">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light">
                <!-- ... existing nav ... -->
                <ul class="navbar-nav ml-auto">
                    <!-- ... other nav items ... -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-user mr-1"></i> 
                            <?php echo htmlspecialchars($_SESSION['first_name']); ?>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="profile.html">Profile</a>
                            <a class="dropdown-item" href="logout.php">Logout</a>
                        </div>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
<div class="main-content">
        <!-- Banner Section -->
        <section class="banner_section">
            <div class="container text-center">
                <h1>NQI Stakeholder Information Update</h1>
                <p>Help us maintain an up-to-date National Quality Infrastructure stakeholder database in Sri Lanka</p>
            </div>
        </section>

        <!-- Form Section -->
        <section class="form-section">
            <div class="container">
                <div class="form-container">
                    <h2 class="form-title">🇱🇰 NQI Stakeholder Information Update – Sri Lanka</h2>
                    <p class="text-center mb-4">This form collects up-to-date contact, services, and accreditation details from NQI bodies and stakeholders in Sri Lanka.</p>

                    <form action="submit_nqi_form.php" method="POST">
                        <!-- Section 1: Organization Details -->
                        <h5 class="section-title">🏢 Organization Details</h5>
                        
                        <div class="form-group">
                            <label for="organization_name">Organization Name *</label>
                            <input type="text" class="form-control" id="organization_name" name="organization_name" required>
                        </div>

                        <div class="form-group">
                            <label for="organization_type">Type of Organization *</label>
                            <select class="form-control" id="organization_type" name="organization_type" required>
                                <option>🏛️ NQI Body (e.g., SLSI, SLAB, MUSSD)</option>
                                <option>⚖️ Regulatory Authority</option>
                                <option>🔬 Testing Laboratory</option>
                                <option>📜 Certification Body</option>
                                <option>🎓 Educational/Research Institute</option>
                                <option>🏭 Industry/Enterprise</option>
                                <option>🏢 Industry Association/Chamber</option>
                                <option>📝 Other</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="organization_type_other">✏️ If Other, please specify</label>
                            <input type="text" class="form-control" id="organization_type_other" name="organization_type_other">
                        </div>

                        <div class="form-group">
                            <label for="contact_person">👤 Contact Person Name</label>
                            <input type="text" class="form-control" id="contact_person" name="contact_person">
                        </div>

                        <div class="form-group">
                            <label for="designation">💼 Designation/Position</label>
                            <input type="text" class="form-control" id="designation" name="designation">
                        </div>

                        <div class="form-group">
                            <label for="email">📧 Email Address *</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>

                        <div class="form-group">
                            <label for="phone">📞 Phone Number *</label>
                            <input type="text" class="form-control" id="phone" name="phone" required>
                        </div>

                        <div class="form-group">
                            <label for="website">🌐 Official Website</label>
                            <input type="url" class="form-control" id="website" name="website">
                        </div>

                        <div class="section-divider"></div>
                        
                        <!-- Section 2: Services -->
                        <h5 class="section-title">📝 Services</h5>
                        
                        <div class="form-group">
                            <label for="core_services">Describe your core activities and services</label>
                            <textarea class="form-control" id="core_services" name="core_services" rows="4"></textarea>
                        </div>

                        <div class="form-group">
                            <label>☑️ Services Offered *</label>
                            <div class="checkbox-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="services[]" value="📘 Standards Development" id="service1">
                                    <label class="form-check-label" for="service1">Standards Development</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="services[]" value="🧪 Calibration" id="service2">
                                    <label class="form-check-label" for="service2">Calibration</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="services[]" value="🛡️ Certification" id="service3">
                                    <label class="form-check-label" for="service3">Product/System Certification</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="services[]" value="🔬 Laboratory Testing" id="service4">
                                    <label class="form-check-label" for="service4">Laboratory Testing</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="services[]" value="🔎 Inspection" id="service5">
                                    <label class="form-check-label" for="service5">Inspection</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="services[]" value="🎯 Training" id="service6">
                                    <label class="form-check-label" for="service6">Training</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="services[]" value="📏 Metrology" id="service7">
                                    <label class="form-check-label" for="service7">Metrology</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="services[]" value="⚖️ Regulatory Oversight" id="service8">
                                    <label class="form-check-label" for="service8">Regulatory Oversight</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="services[]" value="🧠 R&D" id="service9">
                                    <label class="form-check-label" for="service9">Research & Development</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="services[]" value="📝 Other" id="service10">
                                    <label class="form-check-label" for="service10">Other</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="services_other">✏️ If Other, please specify additional services</label>
                            <input type="text" class="form-control" id="services_other" name="services_other">
                        </div>

                        <div class="section-divider"></div>
                        
                        <!-- Section 3: Accreditation -->
                        <h5 class="section-title">🏅 Accreditation</h5>
                        
                        <div class="form-group">
                            <label for="accreditation">Accreditation Status *</label>
                            <select class="form-control" id="accreditation" name="accreditation" required>
                                <option>🟢 Yes (SLAB)</option>
                                <option>🌐 Yes (Other)</option>
                                <option>🔴 No</option>
                                <option>🚫 Not Applicable</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="accreditation_details">📋 Accreditation Details</label>
                            <textarea class="form-control" id="accreditation_details" name="accreditation_details" rows="3"></textarea>
                        </div>

                        <div class="section-divider"></div>
                        
                        <!-- Section 4: Compliance & Regional -->
                        <h5 class="section-title">🔄 Compliance & Regional Information</h5>
                        
                        <div class="form-group">
                            <label for="compliance_update">Update Regulatory Compliance? *</label>
                            <select class="form-control" id="compliance_update" name="compliance_update" required>
                                <option>✅ Yes</option>
                                <option>❌ No</option>
                                <option>🤔 Not Sure</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="regional_branches">🏢 Regional Branches/Labs? *</label>
                            <select class="form-control" id="regional_branches" name="regional_branches" required>
                                <option>✅ Yes</option>
                                <option>❌ No</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="regional_branch_list">📍 List of branches</label>
                            <textarea class="form-control" id="regional_branch_list" name="regional_branch_list" rows="3"></textarea>
                        </div>

                        <div class="section-divider"></div>
                        
                        <!-- Section 5: Additional Notes -->
                        <h5 class="section-title">🗒️ Additional Information</h5>
                        
                        <div class="form-group">
                            <label for="comments">Additional Remarks or Comments</label>
                            <textarea class="form-control" id="comments" name="comments" rows="4"></textarea>
                        </div>

                        <button type="submit" class="btn-submit">Submit Information</button>
                    </form>
                </div>
            </div>
        </section>
    </div>

    <!-- Footer Section -->
    <footer class="footer_section">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                    <h5>Quick Links</h5>
                    <ul>
                        <li><a href="index.html">Home</a></li>
                        <li><a href="introduction.html">Introduction to NQI</a></li>
                        <li><a href="sme-policy.html">SME Development</a></li>
                        <li><a href="standards.html">Standards</a></li>
                        <li><a href="metrology.html">Metrology</a></li>
                        <li><a href="accreditation.html">Accreditation</a></li>
                        <li><a href="awards.html">Quality Awards</a></li>
                    </ul>
                </div>

                <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                    <h5>Contact Information</h5>
                    <ul class="contact_info">
                        <li>
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Ministry of Industry & Commerce, Colombo 01, Sri Lanka</span>
                        </li>
                        <li>
                            <i class="fas fa-phone"></i>
                            <span>+94 11 2 675 675</span>
                        </li>
                        <li>
                            <i class="fas fa-envelope"></i>
                            <span>info@nqi-srilanka.gov.lk</span>
                        </li>
                        <li>
                            <i class="fas fa-clock"></i>
                            <span>Monday-Friday: 8:30 AM - 4:30 PM</span>
                        </li>
                    </ul>
                </div>

                <div class="col-lg-4 col-md-12">
                    <h5>About NQI Sri Lanka</h5>
                    <p>The National Quality Infrastructure (NQI) is the backbone for quality assurance in Sri Lanka,
                        providing the technical framework for standards, metrology, accreditation, and conformity
                        assessment services to enhance the competitiveness of Sri Lankan products and services in global
                        markets.</p>
                </div>
            </div>
        </div>

        <div class="copyright_section">
            <div class="container">
                <p>&copy; 2025 NQI Sri Lanka. All rights reserved. | Developed for SMEs in Sri Lanka</p>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
            // Add active class to current page in navigation
            $('.navbar-nav li a').filter(function() {
                return this.href == location.href;
            }).parent().addClass('active').siblings().removeClass('active');
        });
    </script></body>
</html>