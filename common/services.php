
<section class="py-16 bg-gradient-to-b from-blue-50 to-white">
  <div class="max-w-7xl mx-auto px-6 text-center">
    <h2 class="text-3xl md:text-4xl font-extrabold text-gray-800 mb-3">
      Our Popular Services
    </h2>
    <p class="text-gray-600 max-w-2xl mx-auto mb-12">
      Empowering businesses with seamless registrations, compliance, and tax solutions.
    </p>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
      <?php
        $services = [
          [
            "title" => "Company Registration",
            "desc"  => "Get your company legally registered under the Ministry of Corporate Affairs and start your business journey.",
            "icon"  => "fa-building"
          ],
          [
            "title" => "GST Filing",
            "desc"  => "Simplify your monthly and annual GST filings with expert assistance and on-time reporting.",
            "icon"  => "fa-file-invoice-dollar"
          ],
          [
            "title" => "Trademark Registration",
            "desc"  => "Protect your brand identity and secure your logo or name with an official trademark.",
            "icon"  => "fa-trademark"
          ],
          [
            "title" => "Accounting & Bookkeeping",
            "desc"  => "Maintain your financial records accurately with professional accounting and bookkeeping services.",
            "icon"  => "fa-calculator"
          ],
          [
            "title" => "Income Tax Filing",
            "desc"  => "Ensure accurate and timely income tax filing with expert guidance from professionals.",
            "icon"  => "fa-file-signature"
          ],
          [
            "title" => "Annual Compliance",
            "desc"  => "Stay compliant with all ROC and statutory requirements through our managed compliance support.",
            "icon"  => "fa-clipboard-check"
          ]
        ];

        foreach ($services as $service) {
          echo '
          <div class="bg-white rounded-2xl shadow-md hover:shadow-xl p-8 text-left transition transform hover:-translate-y-1">
            <div class="flex items-center justify-center w-14 h-14 bg-gradient-to-tr from-blue-600 to-blue-400 text-white rounded-xl mb-5">
              <i class="fas '.$service["icon"].' text-2xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-800 mb-3">'.$service["title"].'</h3>
            <p class="text-gray-600 mb-5">'.$service["desc"].'</p>
            <a href="#" class="inline-block px-5 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">Get Started</a>
          </div>';
        }
      ?>
    </div>
  </div>
</section> 

