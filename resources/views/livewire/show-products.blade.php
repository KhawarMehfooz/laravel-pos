<div>
    <div class="overflow-x-auto">
        <ul class="flex items-center gap-4 border p-2 rounded-lg">
            @foreach($categories as $cat)
                <li role="button" class="block px-4 py-1 border rounded-lg">
                    {{$cat->name}}
                </li>
            @endforeach
        </ul>
    </div>
    <div class="my-2 flex flex-wrap item-center gap-4">
        @foreach($products as $product)
            <div wire:key="{{$product->id}}" class="p-4 border rounded-lg w-[300px]">
                <img width="150px" height="150px" class="bg-cover" src="{{asset('storage/' . $product->image)}}" alt="{{$product->name . ' image'}}">
                <h3 class="text-center text-xl">{{$product->name}}</h3>
                <p class="font-semibold text-center">{{ $settings->currency . $product->price}}</p>
            </div>
        @endforeach
    </div>
</div>
