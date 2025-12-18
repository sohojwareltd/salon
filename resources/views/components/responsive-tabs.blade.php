@props(['tabs' => [], 'activeTab' => null])

<div class="responsive-tabs-wrapper">
    <!-- Mobile Dropdown -->
    <div class="tabs-mobile-dropdown">
        <select class="tabs-select" onchange="handleTabChange(this.value)">
            @foreach($tabs as $key => $tab)
                <option value="{{ $key }}" {{ $activeTab === $key ? 'selected' : '' }}>
                    {{ $tab['label'] }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Desktop Tabs -->
    <div class="tabs-container">
        <div class="tabs-list" role="tablist">
            @foreach($tabs as $key => $tab)
                <button 
                    class="tabs-button {{ $activeTab === $key ? 'active' : '' }}"
                    role="tab"
                    aria-selected="{{ $activeTab === $key ? 'true' : 'false' }}"
                    data-tab="{{ $key }}"
                    onclick="switchTab(event, '{{ $key }}')"
                >
                    @if(isset($tab['icon']))
                        <i class="bi bi-{{ $tab['icon'] }}"></i>
                    @endif
                    <span>{{ $tab['label'] }}</span>
                </button>
            @endforeach
        </div>
    </div>

    <!-- Tab Content -->
    <div class="tabs-content">
        @foreach($tabs as $key => $tab)
            <div 
                class="tab-pane {{ $activeTab === $key ? 'active' : '' }}"
                id="tab-{{ $key }}"
                role="tabpanel"
            >
                {{ $tab['content'] }}
            </div>
        @endforeach
    </div>
</div>

<style>
    .responsive-tabs-wrapper {
        width: 100%;
        margin-bottom: 24px;
    }

    /* Mobile Dropdown */
    .tabs-mobile-dropdown {
        display: none;
        margin-bottom: 16px;
    }

    .tabs-select {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 15px;
        font-weight: 600;
        color: #374151;
        background-color: #fff;
        cursor: pointer;
        transition: all 0.3s ease;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23374151' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        padding-right: 36px;
    }

    .tabs-select:hover {
        border-color: #872341;
        box-shadow: 0 4px 12px rgba(135, 35, 65, 0.15);
    }

    .tabs-select:focus {
        outline: none;
        border-color: #872341;
        box-shadow: 0 0 0 3px rgba(135, 35, 65, 0.1);
    }

    /* Desktop Tabs */
    .tabs-container {
        background: #fff;
        border-radius: 16px;
        padding: 8px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        margin-bottom: 24px;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: thin;
        scrollbar-color: rgba(135, 35, 65, 0.3) transparent;
    }

    .tabs-container::-webkit-scrollbar {
        height: 4px;
    }

    .tabs-container::-webkit-scrollbar-track {
        background: transparent;
    }

    .tabs-container::-webkit-scrollbar-thumb {
        background: rgba(135, 35, 65, 0.3);
        border-radius: 4px;
    }

    .tabs-container::-webkit-scrollbar-thumb:hover {
        background: rgba(135, 35, 65, 0.5);
    }

    .tabs-list {
        display: flex;
        gap: 8px;
        flex-wrap: nowrap;
        min-width: min-content;
    }

    .tabs-button {
        flex: 0 0 auto;
        padding: 12px 20px;
        border-radius: 12px;
        border: 2px solid transparent;
        background: transparent;
        color: #64748b;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        position: relative;
    }

    .tabs-button i {
        font-size: 16px;
    }

    .tabs-button:hover {
        background: rgba(135, 35, 65, 0.1);
        color: #872341;
        transform: translateY(-2px);
    }

    .tabs-button.active {
        background: linear-gradient(135deg, #872341, #BE3144);
        color: #fff;
        box-shadow: 0 4px 12px rgba(135, 35, 65, 0.3);
    }

    .tabs-button.active::after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 50%;
        transform: translateX(-50%);
        width: 30px;
        height: 3px;
        background: linear-gradient(135deg, #872341, #BE3144);
        border-radius: 2px;
        display: none;
    }

    /* Tab Content */
    .tabs-content {
        animation: fadeIn 0.3s ease;
    }

    .tab-pane {
        display: none;
        animation: fadeIn 0.3s ease;
    }

    .tab-pane.active {
        display: block;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .tabs-mobile-dropdown {
            display: block;
        }

        .tabs-container {
            display: none;
        }

        .tabs-select {
            padding: 12px 36px 12px 14px;
            font-size: 14px;
        }
    }

    @media (max-width: 640px) {
        .responsive-tabs-wrapper {
            margin-bottom: 16px;
        }

        .tabs-button {
            padding: 10px 16px;
            font-size: 13px;
            gap: 6px;
        }

        .tabs-button i {
            display: none;
        }

        .tabs-container {
            padding: 6px;
            margin-bottom: 16px;
        }
    }

    @media (max-width: 480px) {
        .tabs-button {
            padding: 10px 14px;
            font-size: 12px;
        }

        .tabs-select {
            padding: 10px 32px 10px 12px;
            font-size: 13px;
        }
    }
</style>

<script>
    function handleTabChange(tabKey) {
        switchTab(null, tabKey);
    }

    function switchTab(event, tabKey) {
        if (event) {
            event.preventDefault();
        }

        // Hide all tab panes
        document.querySelectorAll('.tab-pane').forEach(pane => {
            pane.classList.remove('active');
        });

        // Remove active class from all buttons
        document.querySelectorAll('.tabs-button').forEach(btn => {
            btn.classList.remove('active');
            btn.setAttribute('aria-selected', 'false');
        });

        // Show selected tab pane
        const tabPane = document.getElementById(`tab-${tabKey}`);
        if (tabPane) {
            tabPane.classList.add('active');
        }

        // Add active class to clicked button
        const button = document.querySelector(`[data-tab="${tabKey}"]`);
        if (button) {
            button.classList.add('active');
            button.setAttribute('aria-selected', 'true');
        }

        // Update select dropdown
        const select = document.querySelector('.tabs-select');
        if (select) {
            select.value = tabKey;
        }
    }
</script>
