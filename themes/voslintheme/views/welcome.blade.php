<x-app-layout title="home">
    <script>
        function removeElement(element) {
            element.remove();
            this.error = true;
        }
    </script>
    @if(config('settings::theme:enable-tawk') == 1)
    <script type="text/javascript">
        var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
        (function(){
            var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
            s1.async=true;
            s1.src='https://embed.tawk.to/{{ config('settings::theme:tawk-id', '#') }}/{{ config('settings::theme:tawk-widget-id', '#') }}';
            s1.charset='UTF-8';
            s1.setAttribute('crossorigin','*');
            s0.parentNode.insertBefore(s1,s0);
        })();
    </script>
    @endif

    <x-success class="mt-4" />

    @if (config('settings::home_page_text'))
        <div class="content">
            <div class="content-box d-flex">
                <div style="align-items: center; display: flex; flex-direction: column;" class="prose dark:prose-invert min-w-full">
                    @markdownify(config('settings::home_page_text'))
                </div>
            </div>
        </div>
        <br><br>
    @endif
    @if ($categories->count() > 0)
        <div class="content">
            <h2 class="font-semibold text-2xl mb-2 text-secondary-900">Product Categories</h2>
            <div class="grid grid-cols-12 gap-4">
                @foreach ($categories as $category)
                    @if (($category->products()->where('hidden', false)->count() > 0 && !$category->category_id) || $category->children()->count() > 0)
                        <div class="lg:col-span-3 md:col-span-6 col-span-12">
                            <div class="content-box">
                                <div class="flex gap-x-3 items-center mb-2">
                                    @if($category->image)
                                        <img src="/storage/categories/{{ $category->image }}" class="w-14 h-full rounded-md" onerror="removeElement(this);" />
                                    @endif
                                    <div>
                                        <h3 class="font-semibold text-lg">{{ $category->name }}</h3>
                                    </div>
                                </div>
                                <div class="prose dark:prose-invert">@markdownify($category->description)</div>
                                <div class="pt-3 mt-auto">
                                    <style>
                                        .voslin:hover {
                                            border-radius: 50px 0px 50px 0px;
                                            transition: .2s;
                                        }
                                    </style>
                                    <a href="{{ route('products', $category->slug) }}"
                                    class="button button-secondary w-full voslin">{{ __('Browse Category') }}</a>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endif
    @if ($announcements->count() > 0)
        <div class="content">
            <h2 class="font-semibold text-2xl mb-2 text-secondary-900">Recent Announcements</h2>
            <div class="grid grid-cols-12 gap-4">
                @foreach ($announcements->sortByDesc('created_at') as $announcement)
                    <div class="lg:col-span-4 md:col-span-6 col-span-12">
                        <div class="content-box">
                            <h3 class="font-semibold text-lg">{{ $announcement->title }}</h3>
                            <div class="prose dark:prose-invert">@markdownify(strlen($announcement->announcement) > 100 ? substr($announcement->announcement, 0, 100) . '...' : $announcement->announcement)</div>
                            <div class="flex justify-between items-center mt-3">
                                <span class="text-sm text-secondary-600">{{ __('Published') }}
                                    {{ $announcement->created_at->diffForHumans() }}</span>
                                <a href="{{ route('announcements.view', $announcement->id) }}"
                                   class="button button-secondary">{{ __('Read More') }}</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</x-app-layout>
