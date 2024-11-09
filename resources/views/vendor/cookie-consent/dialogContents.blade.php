<style>
    .edu-cookie-popup-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5); /* خلفية شفافة تغطي الشاشة */
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .edu-cookie-popup-container {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        max-width: 500px;
        width: 90%;
        text-align: center;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .edu-cookie-popup-message {
        margin-bottom: 20px;
        font-size: 16px;
        color: #333;
    }

    .edu-cookie-popup-accept-btn {
        padding: 10px 20px;
        background-color: #007bff; /* لون الزر */
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .edu-cookie-popup-accept-btn:hover {
        background-color: #0056b3;
    }
</style>

<div class="edu-cookie-popup-overlay js-edu-cookie-popup">
    <div class="edu-cookie-popup-container">
        <p class="edu-cookie-popup-message">
            {{ __('نستخدم ملفات تعريف الارتباط لضمان حصولك على أفضل تجربة على موقعنا.') }}
        </p>
        <button class="edu-cookie-popup-accept-btn js-edu-cookie-popup-accept">
            {{ __('قبول ملفات تعريف الارتباط') }}
        </button>
    </div>
</div>
