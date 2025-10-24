<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .festival-image {
            width: 100%;
            height: auto;
            object-fit: contain;
            display: block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .content-section {
            padding: 40px 30px;
        }

        .greeting {
            font-size: 24px;
            color: #2c3e50;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }

        .message-text {
            font-size: 16px;
            color: #555;
            text-align: center;
            margin-bottom: 25px;
            line-height: 1.8;
        }

        .holiday-info-box {
            background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
            border-radius: 15px;
            padding: 25px;
            margin: 25px 0;
            border-left: 5px solid #ff6b6b;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .holiday-info-box h3 {
            color: #d63031;
            font-size: 18px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .holiday-info-box p {
            color: #2d3436;
            font-size: 15px;
            margin: 8px 0;
        }

        .date-highlight {
            background: #ffffff;
            padding: 12px;
            border-radius: 8px;
            margin-top: 10px;
            font-weight: bold;
            color: #e74c3c;
        }

        .wishes-section {
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            padding: 30px;
            text-align: center;
            border-radius: 15px;
            margin: 25px 0;
        }

        .wishes-text {
            font-size: 18px;
            color: #2c3e50;
            font-style: italic;
            font-weight: 500;
            line-height: 1.8;
        }

        .decorative-divider {
            text-align: center;
            margin: 25px 0;
            font-size: 30px;
            opacity: 0.6;
        }

        .signature-section {
            text-align: center;
            margin-top: 35px;
            padding-top: 25px;
            border-top: 2px dashed #ddd;
        }

        .regards {
            font-size: 16px;
            color: #666;
            margin-bottom: 8px;
        }

        .company-name {
            font-size: 20px;
            font-weight: bold;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #7f8c8d;
            font-size: 13px;
        }

        .festive-border {
            height: 8px;
            background: linear-gradient(90deg,
                    #ff6b6b 0%,
                    #f093fb 25%,
                    #4facfe 50%,
                    #43e97b 75%,
                    #feca57 100%);
        }
    </style>
</head>

<body>
    <?php
    // Function to determine festival icon, theme, and image
    if (!function_exists('getFestivalDetails')) {
        function getFestivalDetails($festivalName)
        {
            $name = strtolower($festivalName);
    
            // Diwali variations
            if (strpos($name, 'diwali') !== false || strpos($name, 'deepavali') !== false) {
                return ['icon' => '🪔', 'theme' => 'diwali', 'image' => 'Diwali.gif'];
            }
            // Holi variations
            if (strpos($name, 'holi') !== false || strpos($name, 'rangwali') !== false) {
                return ['icon' => '🎨', 'theme' => 'holi', 'image' => 'Holi.gif'];
            }
            // Christmas variations
            if (strpos($name, 'christmas') !== false || strpos($name, 'xmas') !== false) {
                return ['icon' => '🎄', 'theme' => 'christmas', 'image' => 'Christmas.jfif'];
            }
            // New Year variations
            if (strpos($name, 'new year') !== false || strpos($name, 'newyear') !== false) {
                return ['icon' => '🎊', 'theme' => 'newyear', 'image' => 'Newyear.gif'];
            }
            // Eid variations
            if (strpos($name, 'eid') !== false || strpos($name, 'ramadan') !== false || strpos($name, 'bakrid') !== false) {
                return ['icon' => '🌙', 'theme' => 'eid', 'image' => 'eid.jpg'];
            }
            // Ganesh Chaturthi
            if (strpos($name, 'ganesh') !== false || strpos($name, 'ganapati') !== false) {
                return ['icon' => '🐘', 'theme' => 'default', 'image' => 'ganesh.jpg'];
            }
            // Durga Puja / Navratri
            if (strpos($name, 'durga') !== false || strpos($name, 'navratri') !== false || strpos($name, 'dussehra') !== false) {
                return ['icon' => '🙏', 'theme' => 'default', 'image' => 'durga.jpg'];
            }
            // Janmashtami
            if (strpos($name, 'janmashtami') !== false || strpos($name, 'krishna') !== false) {
                return ['icon' => '🦚', 'theme' => 'default', 'image' => 'Janmaashtmi.gif'];
            }
            // Raksha Bandhan
            if (strpos($name, 'rakshabandan') !== false || strpos($name, 'rakhi') !== false || strpos($name, 'raksha bandhan') !== false) {
                return ['icon' => '🎀', 'theme' => 'default', 'image' => 'RakshaBandan.gif'];
            }
            // Bhai Dooj
            if (strpos($name, 'bhai dooj') !== false || strpos($name, 'bhaidooj') !== false || strpos($name, 'bhai duj') !== false) {
                return ['icon' => '👫', 'theme' => 'default', 'image' => 'Bhaidooj.gif'];
            }
            // Independence Day
            if (strpos($name, 'independence') !== false) {
                return ['icon' => '🇮🇳', 'theme' => 'default', 'image' => 'Independence.gif'];
            }
            // Republic Day
            if (strpos($name, 'republic') !== false) {
                return ['icon' => '🇮🇳', 'theme' => 'default', 'image' => 'Republic.gif'];
            }
            // Gandhi Jayanti
            if (strpos($name, 'gandhi') !== false) {
                return ['icon' => '🕊️', 'theme' => 'default', 'image' => 'Gandhi_jayanti.gif'];
            }
            // Maha Shivratri
            if (strpos($name, 'mahashivratri') !== false || strpos($name, 'shivaratri') !== false || strpos($name, 'shivratri') !== false) {
                return ['icon' => '🔱', 'theme' => 'default', 'image' => 'Mahashivratri.jfif'];
            }
            // Pongal / Makar Sankranti
            if (strpos($name, 'pongal') !== false || strpos($name, 'sankranti') !== false) {
                return ['icon' => '🌾', 'theme' => 'default', 'image' => 'pongal.jpg'];
            }
            // Onam
            if (strpos($name, 'onam') !== false) {
                return ['icon' => '🌺', 'theme' => 'default', 'image' => 'onam.jpg'];
            }
            // Guru Nanak Jayanti
            if (strpos($name, 'guru') !== false || strpos($name, 'gurupurab') !== false) {
                return ['icon' => '🙏', 'theme' => 'default', 'image' => 'gurupurab.jpg'];
            }
            // Mahavir Jayanti
            if (strpos($name, 'mahavir') !== false) {
                return ['icon' => '☸️', 'theme' => 'default', 'image' => 'mahavir.jpg'];
            }
            // Buddha Purnima
            if (strpos($name, 'buddha') !== false) {
                return ['icon' => '🧘', 'theme' => 'default', 'image' => 'buddha.jpg'];
            }
            // Default for any other celebration
            return ['icon' => '🎉', 'theme' => 'default', 'image' => 'festival.jpg'];
        }
    }
    
    $festivalDetails = getFestivalDetails($festival->occasion);
    $icon = $festivalDetails['icon'];
    $theme = $festivalDetails['theme'];
    $imagePath = $festivalDetails['image'];
    ?>

    <div class="email-container">
        <div class="festive-border"></div>

        <!-- Festival Image -->
        <img src="{{ asset('images/festivals/' . $imagePath) }}" alt="{{ $festival->occasion }}" class="festival-image">

        <div class="content-section">
            <div class="greeting">
                Dear {{ $name }},
            </div>

            <div class="message-text">
                On this auspicious occasion of <strong>{{ $festival->occasion }}</strong>,
                we extend our warmest wishes to you and your loved ones. May this festival
                illuminate your life with happiness, prosperity, and endless joy!
            </div>

            <div class="decorative-divider">✦ ✦ ✦</div>

            <div class="holiday-info-box">
                <h3>🗓️ Holiday Notice</h3>
                <p>Our office will be closed for the festival celebration:</p>
                <div class="date-highlight">
                    @if ($festival->start_date == $festival->end_date)
                        📅 {{ \Carbon\Carbon::parse($festival->start_date)->format('l, F j, Y') }}
                    @else
                        📅 {{ \Carbon\Carbon::parse($festival->start_date)->format('F j, Y') }}
                        to {{ \Carbon\Carbon::parse($festival->end_date)->format('F j, Y') }}
                    @endif
                </div>
                <p style="margin-top: 15px; font-size: 14px;">
                    We will resume normal operations on the next working day.
                </p>
            </div>

            <div class="wishes-section">
                <div class="wishes-text">
                    May this festival bring new dreams, fresh hopes,
                    and endless possibilities. Wishing you moments of joy,
                    love, and togetherness with your family and friends! 🌟
                </div>
            </div>

            <div class="signature-section">
                <div class="regards">Warm Regards & Best Wishes,</div>
                <div class="company-name">Quantum IT Innovation</div>
            </div>
        </div>

        <div class="footer">
            This is an automated festival greeting from Quantum IT Innovation.<br>
            Wishing you a blessed and memorable celebration! 🎊
        </div>

        <div class="festive-border"></div>
    </div>
</body>

</html>
