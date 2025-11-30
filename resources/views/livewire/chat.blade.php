<div class="flex flex-col h-screen"
    x-data="{ 
        scrollToBottom() { 
            this.$nextTick(() => { 
                const el = this.$refs.messages; 
                if(el) el.scrollLeft = el.scrollWidth; 
            }) 
        } 
    }"
    x-init="scrollToBottom()"
    @message-sent.window="scrollToBottom()">
    
    <!-- Top Header -->
    <div class="bg-gray-900 px-6 py-4 border-b border-gray-800 flex-shrink-0">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"/>
                        <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-semibold text-white">AblyChat</h1>
                    <p class="text-gray-400 text-xs">Chat Global - Mensajería en tiempo real</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2">
                    <span class="flex items-center gap-2 bg-green-500/10 px-3 py-1.5 rounded-lg border border-green-500/20">
                        <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                        <span class="text-green-500 text-xs font-medium">En línea</span>
                    </span>
                </div>
                <!-- User info -->
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-gray-700 rounded-full flex items-center justify-center">
                        <span class="text-white text-xs font-semibold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </span>
                    </div>
                    <span class="text-gray-300 text-sm font-medium">
                        {{ auth()->user()->name }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Horizontal Messages Area -->
    <div class="flex-1 flex flex-col bg-white overflow-hidden">
        <div x-ref="messages" 
            id="messages" 
            class="flex-1 overflow-x-auto overflow-y-hidden p-6 custom-scrollbar"
            style="scrollbar-width: thin; scrollbar-color: #cbd5e1 transparent;">
            
            <div class="flex gap-6 h-full items-end pb-4">
                @forelse($messages as $index => $m)
                    <div class="flex-shrink-0 w-80 animate-fade-in"
                        style="animation-delay: {{ $index * 0.02 }}s">
                        
                        <!-- Message Card -->
                        <div class="bg-gray-50 border border-gray-200 rounded-2xl p-4 hover:bg-gray-100 transition-colors h-auto">
                            <!-- Header with avatar -->
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 rounded-full bg-gray-900 flex items-center justify-center flex-shrink-0 ring-2 ring-gray-100">
                                    <span class="text-white font-semibold text-sm">
                                        {{ strtoupper(substr($m['username'], 0, 1)) }}
                                    </span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-baseline gap-2">
                                        <span class="text-gray-900 font-semibold text-sm truncate">{{ $m['username'] }}</span>
                                        <span class="text-gray-400 text-xs flex-shrink-0">{{ $m['created_at'] ?? 'ahora' }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Message content -->
                            <p class="text-gray-800 text-sm leading-relaxed break-words">{{ $m['content'] }}</p>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center w-full h-full text-gray-400">
                        <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        <p class="text-base font-medium text-gray-600">No hay mensajes aún</p>
                        <p class="text-sm mt-1">¡Sé el primero en enviar un mensaje!</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Fixed Input Area at Bottom -->
        <div class="p-6 bg-white border-t border-gray-200 flex-shrink-0">
            <form wire:submit="sendMessage" class="flex gap-3">
                <input 
                    type="text" 
                    wire:model="content"
                    placeholder="Escribe un mensaje..."
                    class="flex-1 bg-gray-50 border border-gray-300 rounded-xl px-4 py-3 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all text-sm"
                    autocomplete="off">
                
                <button 
                    type="submit"
                    class="bg-gray-900 hover:bg-gray-800 text-white font-medium px-6 py-3 rounded-xl transition-all active:scale-95 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/>
                    </svg>
                    <span class="hidden sm:inline">Enviar</span>
                </button>
            </form>
        </div>
    </div>
</div>