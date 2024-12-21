<x-app-layout clients title="{{ __('Product') }} {{ $product->name }}">
    <div class="content">
        <div class="content-box">
            <div class="mb-4">
                <h1 class="text-2xl font-bold">{{ __('Product: ') }}{{ $product->name }}</h1>
            </div>
            <div class="flex flex-wrap -mx-2 mb-4">
                <div class="w-full md:w-1/2 px-2">
                    <div class="mb-4">
                        <h2 class="text-lg font-bold">{{ __('Product Details') }}</h2>
                    </div>
                    <div class="mb-4">
                        <p>
                            <span class="font-bold">{{ __('Product Name') }}: </span>
                            <br />
                            {{ $product->name }}
                        </p>
                        <p>
                            <span class="font-bold">{{ __('Product Description') }}: </span>
                            @markdownify($product->description)
                        </p>
                        <p>
                            <span class="font-bold">{{ __('Product Price') }}:</span>
                            <br />
                            <x-money :amount="$orderProduct->price" showFree="true" />
                        </p>
                        <p><span class="font-bold">Configurable Options: </span>
                        @php
                            $configurableOptions = $orderProduct->config;
                            $striped = false;
                        @endphp
                        <table class="w-full" style="border: 1px solid; border-color: var(--secondary-50);">
                            <tbody>
                                @foreach($configurableOptions as $configurableOption)
                                    @php
                                        $striped = !$striped;
                                    @endphp
                                    @if($striped)
                                        <tr style="background-color: var(--secondary-100)">
                                            <td>{{$configurableOption->configurableOption->name}}</td>
                                            <td>{{$configurableOption->configurableOptionInput->name}}</td>
                                        </tr>
                                    @else
                                        <tr style="background-color: var(--secondary-50)">
                                            <td>{{$configurableOption->configurableOption->name}}</td>
                                            <td>{{$configurableOption->configurableOptionInput->name}}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="w-full md:w-1/2 px-2">
                    @if ($orderProduct->expiry_date)
                        <div class="mb-4">
                            <h2 class="text-lg font-bold">{{ __('Status') }}</h2>
                        </div>
                        <div class="mb-4">
                            <p>
                                <span class="font-bold">{{ __('Due Date') }}: </span>
                                <br />
                                {{ $orderProduct->expiry_date->toDateString() }}
                            </p>
                            <p>
                                <span class="font-bold">{{ __('Status') }}:</span>
                                <br />
                                {{ $orderProduct->status == 'paid' ? __('Active') : ucfirst($orderProduct->status) }}
                            </p>
                            <p>
                                <span class="font-bold">{{ __('Billing Cycle') }}:</span>
                                <br />
                                {{ ucfirst($orderProduct->billing_cycle) }}
                            </p>
                        </div>
                        @if ($orderProduct->cancellable && !$orderProduct->cancellation()->exists())
                            <x-modal id="cancellationModal">
                                <x-slot name="title">
                                    {{ __('Cancel') }} {{ $product->name }}
                                </x-slot>
                                <form action="{{ route('clients.active-products.cancel', $orderProduct->id) }}"
                                    method="POST" id="cancellationForm">
                                    @csrf
                                    <x-input type="textarea" name="cancellation_reason" label="{{ __('Reason (optional)') }}"
                                        placeholder="{{ __('Reason for cancellation') }}" />

                                    <x-input type="select" name="cancellation_type" label="{{ __('Cancellation Type') }}">
                                        <option value="end_of_billing_period">{{ __('End of Billing Period') }} ({{ $orderProduct->expiry_date->toDateString() }})</option>
                                        <option value="immediate">{{ __('Immediate') }}</option>
                                    </x-input>

                                    <button class="button button-primary mt-4" type="button" data-modal-toggle="confimrationModal" data-modal-hide="cancellationModal">
                                        {{ __('Cancel') }} {{ $product->name }}
                                    </button>
                                </form>
                                <x-slot name="footer">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ __('This will cancel the product and any associated services.') }}
                                    </p>
                                    <br />
                                    <p class="text-sm text-gray-50 dark:text-gray-50">
                                        See our Terms of Service for extra terms regarding Cancellations:
                                        <a class="text-blue-500 hover:text-blue-600" href="https://mythicaldimensions.me/tos/">Terms of Service</a>
                                    </p>
                                </x-slot>
                            </x-modal>

                            <x-modal id="confimrationModal">
                                <x-slot name="title">
                                    {{ __('Confirm Cancellation') }}
                                </x-slot>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('Are you sure you want to cancel this product?') }}
                                    <br />
                                    {{ __('This will cancel (delete) the product and any associated services. This is irreversible.') }}
                                </p>
                                <x-slot name="footer">
                                    <button class="button button-secondary" data-modal-toggle="confimrationModal">
                                        {{ __('No') }}
                                    </button>
                                    <button class="button button-danger"
                                        onclick="document.getElementById('cancellationForm').submit();">
                                        {{ __('Yes') }}
                                    </button>
                                </x-slot>
                            </x-modal>

                            <button class="button button-danger"  data-modal-target="cancellationModal"
                                data-modal-toggle="cancellationModal">
                                {{ __('Cancel Product') }}
                            </button>
                        @endif
                        @if ($orderProduct->cancellation()->exists())
                            <div class="mt-4">
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    @if($orderProduct->status == 'cancelled')
                                        {{ __('This product has been cancelled.') }}
                                    @else
                                        {{ __('This product is pending cancellation.') }}
                                    @endif
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('Cancellation Reason: ') }} {{ $orderProduct->cancellation->reason ?? __('No reason provided') }}
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('Cancellation Date: ') }} {{ $orderProduct->cancellation->created_at->toDateString() }}
                                </p>
                            </div>
                        @endif
                    @endif

                    @if($orderProduct->upgradable)
                        <a class="button button-success" href="{{ route('clients.active-products.upgrade', $orderProduct->id) }}">
                            {{ __('Upgrade Product') }}
                        </a>
                    @endif
                </div>
            </div>
            <div class="sm:flex">
                @if ($link)
                    <div class="sm:flex-1">
                        <a href="{{ $link }}" class="button button-primary" target="_blank">
                            {{ __('Login to Product') }}
                        </a>
                    </div>
                @endif
                @if ($orderProduct->getOpenInvoices()->count() > 0)
                    <div>
                        <a href="{{ route('clients.invoice.show', $orderProduct->getOpenInvoices()->first()->id) }}"
                            class="button button-primary">
                            {{ __('View open Invoice') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="content">
        @isset($views['pages'])
            <ul class="flex flex-wrap text-sm font-medium text-center text-gray-500 dark:text-gray-400">
                <li class="mr-2">
                    <a href="{{ route('clients.active-products.show', [$orderProduct->id]) }}"
                        class="inline-block p-4 text-blue-600 rounded-lg active dark:text-blue-500 border @if (request()->url() === route('clients.active-products.show', $orderProduct->id)) border-primary-400 @else border-transparent @endif hover:border-primary-400 hover:text-primary-400">
                        {{ __('Overview') }}
                    </a>
                </li>
                @foreach ($views['pages'] as $page)
                    <li class="mr-2">
                        <a href="{{ route('clients.active-products.show', [$orderProduct->id]) }}/{{ $page['url'] }}"
                            class="inline-block p-4 text-blue-600 rounded-lg active dark:text-blue-500 border @if (str_contains(request()->url(), $page['url'])) border-primary-400 @else border-transparent @endif hover:border-primary-400 hover:text-primary-400">
                            {{ $page['name'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        @endisset
        @isset($extensionLink)
            @isset($views['pages'])
                @foreach ($views['pages'] as $page)
                    @if ($extensionLink == $page['url'])
                        <div class="content-box">
                            @include($page['template'], $views['data'])
                        </div>
                    @endif
                @endforeach
            @endisset
        @else
            @isset($views['template'])
                <div class="content-box">
                    @include($views['template'], $views['data'])
                </div>
            @endisset
        @endisset
    </div>
</x-app-layout>
