<section class="py-16 md:py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="md:grid md:grid-cols-3 md:gap-12 items-start">
            
            <div class="md:col-span-2 space-y-6">
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-800 mb-8 
    				text-center md:text-left">
    				FAQ's on GST Tax Notice
				</h2>

                <div id="faq-list" class="bg-white rounded-lg shadow-md p-6">
                    </div>
            </div>

            <div class="md:col-span-1 mt-12 md:mt-0 space-y-8">
                
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-5 bg-blue-700 text-white">
                        <h3 class="text-xl font-bold mb-1">GST LUT FORM</h3>
                        <p class="text-sm opacity-90">Filing of GST LUT Form for Exporters - indianfilings</p>
                    </div>
                    <div class="relative h-40 overflow-hidden">
                        <img src="assets/images/container3.jpg" alt="Shipping port with containers" class="w-full h-full object-cover">
                    </div>
                    <div class="p-5">
                        <p class="text-lg font-semibold text-gray-800">GST LUT Form</p>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-5 bg-blue-700 text-white">
                        <h3 class="text-xl font-bold mb-1">GST ANNUAL RETURN FILING (GSTR-9)</h3>
                        <p class="text-sm opacity-90">GST annual return filing for registered taxpayers</p>
                    </div>
                    <div class="relative h-40 overflow-hidden">
                        <img src="assets/images/tax2.jpg" alt="Tax documents and calculator" class="w-full h-full object-cover">
                    </div>
                    <div class="p-5">
                        <p class="text-lg font-semibold text-gray-800">GST Annual Return (GSTR-9)</p>
                    </div>
                </div>

            </div>
            
        </div>
    </div>
</section>

<script>
    // 1. FAQ Data Array
    const faqData = [
        {
            question: "What is a GST notice and why is it issued?",
            answer: "A GST notice is an official communication sent by the tax authorities to a taxpayer, seeking clarification, information, or initiating an action under the GST law. It can be issued for various reasons such as discrepancies in returns, underpayment of tax, non-filing of returns, or other compliance issues."
        },
        {
            question: "What are the common types of GST notices issued to taxpayers?",
            answer: "Common types include notices for non-filing of returns (GSTR-3A), discrepancies in returns (GSTR-3C), audit notices, notices for show cause (SBN), and demands for tax. Each notice type specifies the reason and required action from the taxpayer."
        },
        {
            question: "How do I check if I have received a GST notice?",
            answer: "GST notices are typically communicated through the GST portal under the \"Services > User Services > View Notices and Orders\" section. They are also sent to the registered email address and sometimes via postal mail."
        },
        {
            question: "How to View Notice on GST Portal?",
            answer: "Log in to the GST Portal using your credentials. Navigate to \"Services\" > \"User Services\" > \"View Notices and Orders.\" Here, you can find a list of all notices and orders issued to your GSTIN."
        },
        {
            question: "How should I respond to a GST notice?",
            answer: "The response depends on the type of notice. Generally, it involves preparing a detailed reply, providing supporting documents, and submitting it online through the GST portal within the stipulated time frame mentioned in the notice. It's advisable to consult a tax professional."
        },
        {
            question: "What happens if I ignore a GST notice?",
            answer: "Ignoring a GST notice can lead to severe consequences, including penalties, interest, demand for tax, legal proceedings, and even cancellation of GST registration. It is crucial to respond within the given timeframe."
        },
        {
            question: "How much time do I get to respond to a GST notice?",
            answer: "The response time varies depending on the type of notice, but typically ranges from 7 to 30 days from the date of issuance. The exact deadline will be specified in the notice itself."
        },
        {
            question: "Can a GST notice be served through email or online portal?",
            answer: "Yes, in addition to physical mail, GST notices are primarily served electronically through the GST portal and to the registered email address of the taxpayer."
        },
        {
            question: "What are the most common reasons for receiving a GST notice?",
            answer: "Common reasons include mismatch between GSTR-1 and GSTR-3B, input tax credit (ITC) discrepancies, non-filing or late filing of returns, errors in invoices, and non-compliance with e-way bill rules."
        }
    ];

    // 2. Function to create the HTML string for one FAQ item
    function createFaqItem(faq, isLastItem) {
        // Remove border-b from the very last item for clean layout
        const borderClass = isLastItem ? 'cursor-pointer' : 'border-b border-gray-200 cursor-pointer';

        return `
            <details class="group py-4 ${borderClass}">
                <summary class="flex justify-between items-center text-lg font-semibold text-gray-700 hover:text-blue-600 transition">
                    <span class="flex-1 pr-4">${faq.question}</span>
                    <span class="group-open:rotate-45 transition-transform text-2xl text-gray-500 flex-shrink-0">+</span>
                </summary>
                <p class="mt-4 text-gray-600 leading-relaxed">
                    ${faq.answer}
                </p>
            </details>
        `;
    }

    // 3. Loop, Append Logic, and Accordion Control
    document.addEventListener('DOMContentLoaded', () => {
        const faqContainer = document.getElementById('faq-list');
        if (faqContainer) {
            let allFaqHtml = '';
            const lastIndex = faqData.length - 1;
            
            faqData.forEach((faq, index) => {
                const isLast = (index === lastIndex);
                allFaqHtml += createFaqItem(faq, isLast);
            });

            faqContainer.innerHTML = allFaqHtml;

            // 🌟 ACCORDION CONTROL LOGIC 🌟
            const detailsElements = faqContainer.querySelectorAll('details');

            detailsElements.forEach((targetDetail) => {
                targetDetail.addEventListener('toggle', () => {
                    // Check if the current detail is being opened
                    if (targetDetail.open) {
                        // Loop through all details elements
                        detailsElements.forEach((detail) => {
                            // If a different detail is open, close it
                            if (detail !== targetDetail && detail.open) {
                                detail.open = false;
                            }
                        });
                    }
                });
            });
        }
    });
</script>