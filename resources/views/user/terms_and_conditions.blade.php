@extends('user.layout')
@section('header')
    <ion-title>Terms and Condition</ion-title>
    <ion-button slot="start" href="/user/dashboard">
        <ion-icon name="arrow-back-outline"></ion-icon>
    </ion-button>
@endsection

@section('content')
    <div class="terms-content ion-padding" mode="ios">
        <!-- <h3>Term & Condition</h3> -->
        <p>1) To optimize and reset your account, you must first complete all ratings with a minimum amount of USD 50 and a
            minimum account reset amount of USD 100.</p>
        <p>1.1) Users who have completed a set of tasks should contact customer service to request a reset for the next set
            of tasks.</p>
        <p>2) User withdrawals and system withdrawal requirements / security of user funds</p>
        <p>2.1) Each user needs to complete all the optimization tasks before they can meet the system withdrawal
            requirements.</p>
        <p>2.2) In order to avoid any loss of funds, all withdrawals are processed automatically by the system and not
            manually.</p>
        <p>2.3) All users are not allowed to apply for withdrawal in the middle of a task to avoid affecting the merchant's
            operation.</p>
        <p>2.4) Users' funds are completely safe on the platform and the platform will be liable for any accidental loss.
        </p>
        <p>3) Please do not disclose your account password and withdrawal password to others. The platform will not be held
            responsible for any loss or damage caused.</p>
        <p>3.1) All users are advised to keep their accounts secure to avoid disclosure.</p>
        <p>3.2) The platform is not responsible for any accidental disclosure of accounts.</p>
        <p>3.3) Because of the financial implications of the accounts, it is important not to disclose them to avoid
            unnecessary problems.</p>
        <p>3.4) Withdrawal password: It is recommended that you do not set a birthday password, ID card number or mobile
            phone number, etc. It is recommended that you set a more difficult password to protect your funds.</p>
        <p>3.5) If you forget your password, you can reset it by contacting the online service and be sure to change it
            yourself afterwards.</p>
        <p>4) Optimization ratings are randomly assigned by the system and therefore cannot be changed, canceled,
            controlled, or skipped in any way.</p>
        <p>4.1) Due to the large number of users on the platform, it is not possible to distribute combination products
            manually, so all task product data are distributed randomly by the system.</p>
        <p>4.2) Combination products are randomly released by the system and cannot be changed/cancelled/skipped by any
            user/staff.</p>
        <p>5) Legal action will be taken in the event of misuse of the account.</p>
        <p>6) Each product data comes from a different merchant, no deposit for more than 10 minutes, and each deposit must
            be made with the online service to confirm the merchant's deposit detail.</p>
        <p>7) The platform will not be held responsible for any deposits made to the wrong detail.</p>
        <p>8) Each time the product data is optimized, the user must complete it within 24 hours. If it is not completed and
            the merchant is not notified to apply for an extension, resulting in complaints from the merchant, the user will
            be responsible for breach of contract and may have his or her credit score deducted.</p>
    </div>
@endsection
