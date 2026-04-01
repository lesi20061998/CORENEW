<div class="space-y-2 mb-4" x-data="colorPicker('{{ $value }}')">
    <label class="text-[10px] font-black text-slate-400 uppercase tracking-[.2em] block pl-1">{{ $label }}</label>
    <div class="relative group">
        <div class="flex items-center bg-white border border-slate-100 rounded-2xl p-2 pr-4 shadow-sm hover:border-slate-200 focus-within:ring-4 focus-within:ring-slate-100 transition-all">
            {{-- Color Box & Real Input --}}
            <div class="relative w-11 h-11 shrink-0 rounded-xl overflow-hidden shadow-inner border border-slate-100">
                <input type="color" 
                       x-model="hex" 
                       @input="updateFromHex()" 
                       class="absolute inset-0 w-[150%] h-[150%] -m-[25%] cursor-pointer border-none p-0 outline-none">
            </div>

            {{-- Hex Input --}}
            <div class="ml-4 flex-1">
                <input type="text" 
                       name="{{ $name }}" 
                       x-model="hex" 
                       @input="updateFromHex()"
                       placeholder="#000000"
                       class="w-full bg-transparent border-none p-0 text-sm font-black text-slate-900 focus:outline-none focus:ring-0 uppercase tracking-widest leading-none">
            </div>

            {{-- RGB Info (Discrete) --}}
            <div class="hidden sm:flex items-center gap-1.5 opacity-40 group-hover:opacity-100 transition-opacity">
                <div class="flex items-center gap-0.5">
                    <span class="text-[8px] font-bold text-slate-400">R</span>
                    <span class="text-[10px] font-black text-slate-900" x-text="r"></span>
                </div>
                <div class="flex items-center gap-0.5">
                    <span class="text-[8px] font-bold text-slate-400">G</span>
                    <span class="text-[10px] font-black text-slate-900" x-text="g"></span>
                </div>
                <div class="flex items-center gap-0.5">
                    <span class="text-[8px] font-bold text-slate-400">B</span>
                    <span class="text-[10px] font-black text-slate-900" x-text="b"></span>
                </div>
            </div>
        </div>
    </div>
</div>
