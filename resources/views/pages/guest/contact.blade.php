<x-layouts.guest>
    <x-slot name="title">Contact Support | Falcon x</x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="grid lg:grid-cols-2 gap-16">
            <!-- Contact Info -->
            <div>
                <h1 class="text-4xl font-black mb-6">Get in Touch</h1>
                <p class="text-slate-400 text-lg mb-10">Our support team is available 24/7 to assist you. Whether you
                    have questions about setting up your bot or need technical help, we're here for you.</p>

                <div class="space-y-8">
                    <div class="flex items-start gap-4">
                        <div
                            class="w-12 h-12 rounded-lg bg-surface-dark border border-border-dark flex items-center justify-center text-primary shrink-0">
                            <span class="material-symbols-outlined">mail</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white mb-1">Email Support</h3>
                            <p class="text-slate-400 mb-2">For general inquiries and technical support.</p>
                            <a href="mailto:support@falcnx.com"
                                class="text-primary hover:text-white transition-colors font-medium">support@falcnx.com</a>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div
                            class="w-12 h-12 rounded-lg bg-surface-dark border border-border-dark flex items-center justify-center text-primary shrink-0">
                            <span class="material-symbols-outlined">chat</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white mb-1">Live Chat</h3>
                            <p class="text-slate-400 mb-2">Instant answers for urgent issues.</p>
                            <button class="text-primary hover:text-white transition-colors font-medium">Start
                                Chat</button>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div
                            class="w-12 h-12 rounded-lg bg-surface-dark border border-border-dark flex items-center justify-center text-primary shrink-0">
                            <span class="material-symbols-outlined">location_on</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white mb-1">Office</h3>
                            <p class="text-slate-400">123 Tech Plaza, Silicon Valley<br>San Francisco, CA 94000</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="bg-surface-dark p-8 rounded-2xl border border-border-dark relative overflow-hidden">
                <div
                    class="absolute top-0 right-0 w-32 h-32 bg-primary/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2">
                </div>

                <h2 class="text-2xl font-bold mb-6">Send us a message</h2>
                <form class="space-y-6">
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-400 mb-2">Name</label>
                            <input type="text"
                                class="w-full bg-background-dark border border-border-dark rounded-lg px-4 py-3 text-white focus:outline-primary focus:ring-primary focus:border-primary"
                                placeholder="Your name">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-400 mb-2">Email</label>
                            <input type="email"
                                class="w-full bg-background-dark border border-border-dark rounded-lg px-4 py-3 text-white focus:outline-primary focus:ring-primary focus:border-primary"
                                placeholder="your@email.com">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-400 mb-2">Subject</label>
                        <select
                            class="w-full bg-background-dark border border-border-dark rounded-lg px-4 py-3 text-white focus:outline-primary focus:ring-primary focus:border-primary">
                            <option>General Inquiry</option>
                            <option>Technical Support</option>
                            <option>Billing Issue</option>
                            <option>Partnership</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-400 mb-2">Message</label>
                        <textarea rows="4"
                            class="w-full bg-background-dark border border-border-dark rounded-lg px-4 py-3 text-white focus:outline-primary focus:ring-primary focus:border-primary"
                            placeholder="How can we help you?"></textarea>
                    </div>
                    <button type="button"
                        class="w-full bg-primary hover:bg-primary/90 text-white font-bold py-3 rounded-xl transition-all shadow-lg shadow-primary/20">
                        Send Message
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.guest>