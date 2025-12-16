<div x-data="{
    open: false,
    message: '',
    loading: false,
    messages: [
        { role: 'system', content: 'Halo! Ada yang bisa saya bantu hari ini? 🛒' }
    ],
    toggleChat() {
        this.open = !this.open;
        if (this.open) {
            this.$nextTick(() => this.scrollToBottom());
        }
    },
    sendMessage() {
        if (this.message.trim() === '') return;

        // User message
        this.messages.push({ role: 'user', content: this.message });
        const userMsg = this.message;
        this.message = '';
        this.loading = true;
        this.$nextTick(() => this.scrollToBottom());

        fetch('{{ route('ai.chat') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                },
                body: JSON.stringify({ message: userMsg })
            })
            .then(response => response.json())
            .then(data => {
                this.messages.push({ role: 'system', content: data.reply });
                this.$nextTick(() => this.scrollToBottom());
            })
            .catch(error => {
                console.error('Error:', error);
                this.messages.push({ role: 'system', content: 'Maaf, sedang ada gangguan jaringan.' });
            })
            .finally(() => {
                this.loading = false;
            });
    },
    scrollToBottom() {
        const chatContainer = this.$refs.chatContainer;
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }
}" class="fixed bottom-6 right-6 z-50">

    <!-- Chat Button -->
    <button @click="toggleChat"
        class="bg-blue-600 hover:bg-blue-700 text-white rounded-full p-4 shadow-lg transition-transform transform hover:scale-105"
        :class="{ 'rotate-180': open }">
        <template x-if="!open">
            <i class="fa-solid fa-comments text-2xl"></i>
        </template>
        <template x-if="open">
            <i class="fa-solid fa-times text-2xl"></i>
        </template>
    </button>

    <!-- Chat Window -->
    <div x-show="open" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-4"
        class="absolute bottom-20 right-0 w-80 sm:w-96 bg-white rounded-2xl shadow-2xl border border-gray-200 overflow-hidden flex flex-col"
        style="height: 500px; max-height: 80vh;">

        <!-- Header -->
        <div class="bg-blue-600 p-4 text-white flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <i class="fa-solid fa-robot"></i>
                <h3 class="font-bold">CS MyMart AI</h3>
            </div>
        </div>

        <!-- Messages Area -->
        <div x-ref="chatContainer" class="flex-1 p-4 overflow-y-auto bg-gray-50 space-y-3">
            <template x-for="(msg, index) in messages" :key="index">
                <div class="flex" :class="msg.role === 'user' ? 'justify-end' : 'justify-start'">
                    <div class="max-w-[80%] rounded-lg px-4 py-2 text-sm shadow-sm"
                        :class="msg.role === 'user' ? 'bg-blue-600 text-white rounded-br-none' :
                            'bg-white text-gray-800 border border-gray-200 rounded-bl-none'">
                        <p x-text="msg.content" class="leading-relaxed"></p>
                    </div>
                </div>
            </template>

            <!-- Loading Indicator -->
            <div x-show="loading" class="flex justify-start">
                <div class="bg-gray-200 rounded-full px-4 py-2 flex items-center space-x-1">
                    <div class="w-2 h-2 bg-gray-500 rounded-full animate-bounce"></div>
                    <div class="w-2 h-2 bg-gray-500 rounded-full animate-bounce delay-75"></div>
                    <div class="w-2 h-2 bg-gray-500 rounded-full animate-bounce delay-150"></div>
                </div>
            </div>
        </div>

        <!-- Input Area -->
        <div class="p-3 bg-white border-t border-gray-100">
            <form @submit.prevent="sendMessage" class="flex items-center space-x-2">
                <input type="text" x-model="message" placeholder="Tanya sesuatu..."
                    class="flex-1 border-gray-300 rounded-full focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-sm py-2 px-4 shadow-sm"
                    :disabled="loading">
                <button type="submit"
                    class="bg-blue-600 text-white rounded-full p-2 h-10 w-10 flex items-center justify-center hover:bg-blue-700 disabled:opacity-50 shadow-md transition"
                    :disabled="loading || message.trim() === ''">
                    <i class="fa-solid fa-paper-plane text-sm"></i>
                </button>
            </form>
        </div>
    </div>
</div>
