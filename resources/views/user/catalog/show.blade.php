<x-layouts.app title="{{ $book->title }}">
    <div class="space-y-6">
        <!-- Back Link -->
        <div class="pb-2 border-b border-zinc-200/70">
            <a href="{{ route('user.catalog.index') }}" class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold text-zinc-600 hover:text-zinc-900 bg-white border border-zinc-200/80 shadow-2xs transition-colors">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                <span>Back to Catalog</span>
            </a>
        </div>

        <!-- Book Detail Main Card -->
        <div class="bg-white rounded-2xl border border-zinc-200/80 shadow-2xs p-5 sm:p-7">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 sm:gap-8 items-start">
                <!-- Cover Image (4 cols) -->
                <div class="md:col-span-4 lg:col-span-3">
                    <div class="rounded-xl overflow-hidden border border-zinc-200/80 shadow-xs aspect-[3/4] bg-zinc-100">
                        <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                    </div>
                </div>

                <!-- Info & Borrow Form (8 cols) -->
                <div class="md:col-span-8 lg:col-span-9 space-y-5">
                    <div>
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-semibold bg-[#EEF2FF] text-[#4F46E5]">
                                {{ $book->category->name ?? 'General Category' }}
                            </span>
                            @if($book->is_available && $book->available_stock > 0)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-semibold bg-[#F0FDF4] text-[#16A34A]">
                                    Available ({{ $book->available_stock }} of {{ $book->stock }} copies)
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-semibold bg-[#FEECEC] text-[#DC2626]">
                                    Out of Stock / Checked Out
                                </span>
                            @endif
                        </div>
                        <h1 class="text-xl sm:text-2xl font-bold text-zinc-900 tracking-tight">{{ $book->title }}</h1>
                        <p class="text-xs font-medium text-zinc-500 mt-1">Author: {{ $book->author }} • Publisher: {{ $book->publisher }} ({{ $book->publication_year }})</p>
                    </div>

                    <!-- Metadata Grid -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 p-3.5 rounded-xl bg-zinc-50/70 border border-zinc-200/80 text-xs">
                        <div>
                            <span class="text-zinc-400 block font-medium text-[10px]">ISBN</span>
                            <span class="font-mono font-semibold text-zinc-800 text-xs">{{ $book->isbn }}</span>
                        </div>
                        <div>
                            <span class="text-zinc-400 block font-medium text-[10px]">Publication Year</span>
                            <span class="font-semibold text-zinc-800 text-xs">{{ $book->publication_year }}</span>
                        </div>
                        <div>
                            <span class="text-zinc-400 block font-medium text-[10px]">Shelf Location</span>
                            <span class="font-mono font-semibold text-zinc-800 text-xs flex items-center space-x-1">
                                <i data-lucide="map-pin" class="w-3 h-3 text-brand-orange"></i>
                                <span>{{ $book->shelf_location }}</span>
                            </span>
                        </div>
                    </div>

                    <!-- Synopsis -->
                    <div class="space-y-1.5">
                        <h3 class="text-[11px] font-semibold text-zinc-700">Synopsis & Summary</h3>
                        <p class="text-xs text-zinc-600 leading-relaxed whitespace-pre-line">
                            {{ $book->description ?: 'No synopsis available for this book.' }}
                        </p>
                    </div>

                    <!-- Borrowing Action Form -->
                    <div class="pt-4 border-t border-zinc-100">
                        @if($book->is_available && $book->available_stock > 0)
                            <div class="p-4 rounded-xl bg-zinc-50/70 border border-zinc-200/80 space-y-3">
                                <div>
                                    <h4 class="font-bold text-xs text-zinc-900">Request to Borrow This Book</h4>
                                    <p class="text-[11px] text-zinc-500 mt-0.5">Application will be processed by library staff. Maximum loan period is 14 days.</p>
                                </div>

                                <form method="POST" action="{{ route('user.borrowings.store') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-2.5 items-end">
                                    @csrf
                                    <input type="hidden" name="book_id" value="{{ $book->id }}">

                                    <div class="sm:col-span-4">
                                        <label for="duration_days" class="block text-[10px] font-semibold text-zinc-700 mb-1">
                                            Duration
                                        </label>
                                        <select
                                            id="duration_days"
                                            name="duration_days"
                                            required
                                            class="w-full px-2.5 py-1.5 text-xs bg-white border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-zinc-400 text-zinc-800"
                                        >
                                            <option value="3">3 Days</option>
                                            <option value="7" selected>7 Days (Standard)</option>
                                            <option value="10">10 Days</option>
                                            <option value="14">14 Days (Maximum)</option>
                                        </select>
                                    </div>

                                    <div class="sm:col-span-5">
                                        <label for="notes" class="block text-[10px] font-semibold text-zinc-700 mb-1">
                                            Purpose Note (Optional)
                                        </label>
                                        <input
                                            type="text"
                                            id="notes"
                                            name="notes"
                                            placeholder="e.g. Research / coursework"
                                            class="w-full px-2.5 py-1.5 text-xs bg-white border border-zinc-200/80 rounded-xl focus:outline-none focus:ring-1 focus:ring-zinc-400 text-zinc-900"
                                        >
                                    </div>

                                    <div class="sm:col-span-3">
                                        <button
                                            type="submit"
                                            class="w-full py-1.5 px-3 bg-zinc-950 hover:bg-zinc-800 text-white text-xs font-semibold rounded-xl shadow-2xs transition-all flex items-center justify-center space-x-1.5"
                                        >
                                            <i data-lucide="book-open" class="w-3.5 h-3.5"></i>
                                            <span>Request Loan</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @else
                            <div class="p-3.5 rounded-xl bg-[#FEECEC] border border-red-200 text-red-700 text-xs flex items-center space-x-2">
                                <i data-lucide="alert-octagon" class="w-4 h-4 text-red-500 flex-shrink-0"></i>
                                <span>All copies are currently borrowed by members. Please check back later.</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Books in Same Category -->
        @if($relatedBooks->count() > 0)
            <div class="bg-white rounded-2xl border border-zinc-200/80 shadow-2xs p-5 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-zinc-100">
                    <div>
                        <h3 class="font-bold text-sm text-zinc-900">More in {{ $book->category->name ?? 'This Category' }}</h3>
                        <p class="text-xs text-zinc-400 mt-0.5">Explore related titles in the same discipline</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3.5">
                    @foreach($relatedBooks as $rb)
                        <a href="{{ route('user.catalog.show', $rb->slug) }}" class="group block rounded-xl border border-zinc-200/80 p-2.5 hover:border-zinc-300 transition-all">
                            <div class="aspect-[3/4] rounded-lg overflow-hidden bg-zinc-100 mb-2 border border-zinc-200/60">
                                <img src="{{ $rb->cover_url }}" alt="{{ $rb->title }}" class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-200">
                            </div>
                            <h4 class="font-semibold text-xs text-zinc-900 line-clamp-1 group-hover:text-brand-orange transition-colors">{{ $rb->title }}</h4>
                            <p class="text-[10px] text-zinc-400 line-clamp-1 mt-0.5">{{ $rb->author }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-layouts.app>
