<x-filament-panels::page class="">
    <div class="bg-neutral-100 rounded-md border">
        <section class="p-6 flex gap-8 flex-col md:flex-row">
          <div style="min-height: calc(100vh - 200px);" class="md:w-[70%]">
            <!-- Categories List -->
            <ul class="flex items-center gap-4 overflow-x-auto bg-neutral-200 border ring-gray-100 text-lg py-1 px-2 rounded-lg">
                @foreach($categories as $category)
              <li class="">
                <button wire:key="{{$category->id}}" class="font-semibold border border-neutral-200 py-2 px-4 bg-neutral-100 rounded-xl">{{$category->name}}</button>
              </li>
              @endforeach
            </ul>
            <!-- Products Grid -->
            <div style="max-height: calc(100vh - 150px);" class="overflow-y-auto mt-4 grid gap-4 grid-cols-1 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">
                @foreach($products as $product)
                    <article wire:key="{{$product->id}}" wire:click="addToCart({{$product->id}})" data-filter="{{strtolower($product->category->name) }}" class="md:w-48 border bg-white p-4 rounded-lg ring-gray-100 cursor-pointer">
                        <img src="{{asset('storage/' . $product->image)}}" alt="{{$product->name . ' image'}}" alt="" class="rounded-full h-40 w-40 aspect-square mx-auto object-cover ">
                        <h2 class="mt-2 text-xl text-center">{{$product->name}}</h2>
                        <p class="text-center font-bold text-xl">{{$settings->currency . $product->price}}</p>
                    </article>
                @endforeach
            </div>
          </div>
          <!-- cart -->
          <div style="min-height: calc(100vh - 200px);" class="relative bg-white rounded-md border md:w-[30%]">
            <div class="p-3">
              <!-- Customers -->
              <div class="flex items-center gap-4 border-b pb-4">
                <fieldset class="w-full">
                  <form action="w-full">
                    <select class="py-2 w-full rounded border focus:outline-none cursor-pointer" name="customer" id="">
                      <option value="">Walk in customer</option>
                      <option value="">John Doe </option>
                    </select>
                  </form>
                </fieldset>
                <button class="bg-yellow-500 py-1 text-xl font-semibold px-4 rounded-md border-2 border-yellow-600">+</button>
              </div>
              <!-- Items In Cart -->
               <div class="mt-4">
                <div class="flex flex-col">
                  <div class="-m-1.5 overflow-x-auto">
                    <div class="p-1.5 min-w-full inline-block align-middle">
                      <div class="border rounded-md overflow-hidden  ">
                        <table class="min-w-full">
                          <colgroup>
                            <col width="40%">
                            <col width="20%">
                            <col width="20%">
                            <col width="20%">
                          </colgroup>
                          <thead class="border-b">
                            <tr>
                              <th scope="col" class="pl-3 py-3 text-start text-xs font-medium text-gray-500 uppercase">Product</th>
                              <th scope="col" class=" py-3 text-start text-xs font-medium text-gray-500 uppercase">Quantity</th>   
                              <th scope="col" class=" pl-3 py-3 text-start text-xs font-medium text-gray-500 uppercase">price</th>
                              <th scope="col" class="pr-3 py-3 text-end text-xs font-medium text-gray-500 uppercase">Action</th>
                            </tr>
                          </thead>
                        </table>
                        <div class="max-h-[400px] overflow-y-auto">
                          <table class="min-w-full divide-y divide-gray-200  ">
                            <colgroup>
                              <col width="40%">
                              <col width="20%">
                              <col width="20%">
                              <col width="20%">
                            </colgroup>
                            <tbody class="divide-y divide-gray-200">
                              @foreach($products_in_cart as $product)
                              <tr wire:key="{{$product['id']}}">
                                <td class="pl-3 py-4 whitespace-wrap text-sm font-medium text-gray-800 ">{{$product['name']}}</td>
                                <td class="py-4 whitespace-nowrap text-sm text-gray-800 ">
                                    <input class="w-full border py-1 outline-none px-2" value="{{$product['quantity']}}" type="number" name="" id="">
                                </td>
                                <td class="pl-3 py-4 whitespace-nowrap text-sm text-gray-800">{{$settings->currency . $product['price']}}</td>
                                <td class="pr-3 py-4 whitespace-nowrap text-end text-sm font-medium">
                                  <button wire:click="removeFromCart({{$product['id']}})" type="button" class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-blue-600 hover:text-blue-800 disabled:opacity-50 ">Delete</button>
                                </td>
                              </tr>
                              @endforeach
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>       
               </div>
            </div> 
            <div class="absolute bottom-0 mt-4 w-full border-t">
              <div class="p-3">
                <div class="my-4 flex items-center justify-between">
                  <p class="text-lg ">Subtotal</p>
                  <p class="text-lg font-semibold">{{$settings->currency . $subtotal}}</p>
                </div>
                <div class="my-4 flex items-center justify-between">
                  <p class="text-lg ">Discount (%)</p>
                  <p class="text-lg font-semibold">
                    <input class="outline-none border w-[80px] px-2 text-lg" type="number" min="0" name="discount" id="discount"  wire:model="discount"  >
                  </p>
                </div>
                @if($settings->charge_tax === 1)
                <div class="my-4 flex items-center justify-between">
                  <p class="text-lg ">VAT({{$settings->tax_percentage}}%)</p>
                  <p class="text-lg font-semibold">{{$settings->currency . $vat_amount}}</p>
                </div>
                @endif
                <div class="my-4 flex items-center justify-between">
                  <p class="text-lg ">Grand Total</p>
                  <p class="text-3xl font-semibold">{{$settings->currency . $grand_total}}</p>
                </div>
                <div class="flex items-center justify-center gap-8">
                  <button class="bg-green-500 rounded-md text-lg py-2 w-[50%] text-white font-semibold">Hold Order</button>
                  <button class="bg-sky-500 rounded-md text-lg text-white font-semibold py-2 w-[50%]">Pay</button>
                </div>
              </div>
          </div>
          </div>
        </section>
      </div>
</x-filament-panels::page>
