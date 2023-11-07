<div class="tasks-card flex flex-col rounded-3xl md:rounded-[40px] bg-card">
    <div class="tasks-card-photo overflow-hidden h-40 xs:h-48 sm:h-[280px] rounded-3xl md:rounded-[40px]">
        <a href="{{ route('materials.show', $item) }}">
            <img src=""
                 class="object-cover w-full h-full"
                 alt="{{ $item->title }}">
        </a>
    </div>
    <div class="grow flex flex-col pt-6 sm:pt-10 pb-6 sm:pb-10 2xl:pb-14 px-5 sm:px-8 2xl:px-12">
        <h3 class="text-md md:text-lg 2xl:text-xl font-black">
            {{ $item->title }}
        </h3>

        <div class="mt-auto">
            <div class="flex flex-wrap sm:items-center justify-center sm:justify-between mt-8 sm:mt-10">
                <x-button href="{{ route('materials.show', $item) }}">
                    Подробнее
                </x-button>
            </div>
        </div>
    </div>
</div>
