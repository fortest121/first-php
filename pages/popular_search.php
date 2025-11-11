<div class="bg-gradient-to-r from-blue-50 to-blue-100 py-10">
  <div class="max-w-6xl mx-auto px-5">
    <div class="text-center mb-6">
      <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Popular Searches</h2>
      <div class="mt-2 w-20 mx-auto h-1 bg-blue-600 rounded-full"></div>
    </div>

    <div class="flex flex-wrap justify-center gap-3">
      <?php
        $tags = [
          "Startup Registration", "Company Incorporation", "GST Filing", "Trademark Registration",
          "Income Tax Return", "Business License", "MSME Registration", "Import Export Code (IEC)",
          "Digital Signature (DSC)", "Accounting & Bookkeeping", "Private Limited Company",
          "LLP Formation", "Startup India Recognition", "ESI & PF Registration", "Payroll Management",
          "TDS Filing", "ISO Certification", "FSSAI License", "Udyam Registration",
          "Shop & Establishment Act", "Legal Agreements", "Business Consultancy",
          "ROC Filing", "Annual Compliance", "Auditing Services"
        ];

        foreach ($tags as $tag) {
          echo '<a href="#" class="tag px-4 py-2 text-sm bg-white shadow-sm border border-gray-200 rounded-full hover:bg-blue-600 hover:text-white transition">'.$tag.'</a>';
        }
      ?>
    </div>
  </div>
</div>
