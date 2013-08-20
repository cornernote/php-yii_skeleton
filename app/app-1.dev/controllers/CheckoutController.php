<?php

/**
 *
 * @package app.controller
 * @author Brett O'Donnell <brett@mrphp.com.au>
 */
class CheckoutController extends WebController
{

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow',
                'actions' => array('ipn'),
                'users' => array('*'),
            ),
            array('allow',
                'actions' => array('plan', 'success'),
                'roles' => array('locksmith'),
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }

    /**
     * Change Plan
     * @throws CHttpException
     */
    public function actionPlan()
    {
        /** @var $locksmith Locksmith **/
        $locksmith = $this->loadModel(user()->id, 'Locksmith');

        if (isset($_POST['LocksmithEav'])) {
            $plan = $locksmith->getPlan();
            if ($plan['subscription'] == 'active') {
                user()->addFlash('Your plan cannot be changed while it is active.', 'warning');
            }
            else {
                $attributes = $_POST['LocksmithEav'];
                if ($attributes['locksmith_plan'] != 'free' && strtotime($plan['expires']) < time()) {
                    $attributes['locksmith_plan_expires'] = date('Y-m-d H:i:s', strtotime('+1 minute'));
                }
                if ($locksmith->setEavAttributes($attributes, true)) {
                    user()->addFlash('Your plan has been saved.', 'success');
                    $this->redirect(ReturnUrl::getUrl(array('/checkout/plan')));
                }
                else {
                    user()->addFlash('Your plan could not be saved.', 'warning');
                }
            }
        }

        $this->render('plan', array(
            'locksmith' => $locksmith,
        ));
    }


    /**
     * Successful Payment
     * @throws CHttpException
     */
    public function actionSuccess()
    {
        $this->render('success');
    }

    /**
     * PayPal IPN
     * @ref https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_admin_IPNIntro
     * @eg of get data - mc_gross=19.95&protection_eligibility=Eligible&address_status=confirmed&payer_id=LPLWNMTBWMFAY&tax=0.00&address_street=1+Main+St&payment_date=20%3A12%3A59+Jan+13%2C+2009+PST&payment_status=Completed&charset=windows-1252&address_zip=95131&first_name=Test&mc_fee=0.88&address_country_code=US&address_name=Test+User&notify_version=2.6&custom=&payer_status=verified&address_country=United+States&address_city=San+Jose&quantity=1&verify_sign=AtkOfCXbDm2hu0ZELryHFjY-Vb7PAUvS6nMXgysbElEn9v-1XcmSoGtf&payer_email=gpmac_1231902590_per%40paypal.com&txn_id=61E67681CH3238416&payment_type=instant&last_name=User&address_state=CA&receiver_email=gpmac_1231902686_biz%40paypal.com&payment_fee=0.88&receiver_id=S8XGHLYDW9T3S&txn_type=express_checkout&item_name=&mc_currency=USD&item_number=&residence_country=US&test_ipn=1&handling_amount=0.00&transaction_subject=&payment_gross=19.95&shipping=0.00
     */
    public function actionIpn()
    {
        // save the transaction
        $transaction = new Transaction();
        $transaction->user_id = isset($_POST['custom']) ? $_POST['custom'] : 0;
        $transaction->type = isset($_POST['txn_type']) ? $_POST['txn_type'] : 'unknown';
        $transaction->save(false);
        $transaction->setEavAttributes($_POST, true);

        // handle signup
        if ($transaction->type == 'subscr_signup') {
            $locksmith = Locksmith::model()->findByPk($transaction->user_id);
            $locksmith_plan = isset($_POST['item_number']) ? str_replace('locksmith_plan_', '', trim($_POST['item_number'])) : false;
            if ($locksmith && $locksmith_plan) {
                $locksmith->setEavAttribute('locksmith_plan', $locksmith_plan, true);
                $locksmith->setEavAttribute('locksmith_plan_expires', date('Y-m-d H:i:d', strtotime('+33 days')), true);
                $locksmith->setEavAttribute('locksmith_plan_subscription', 'active', true);
            }
        }
        // handle cancel
        if ($transaction->type == 'subscr_cancel') {
            $locksmith = Locksmith::model()->findByPk($transaction->user_id);
            if ($locksmith) {
                $locksmith->setEavAttribute('locksmith_plan_subscription', 'cancelled', true);
            }
        }

        // TODO handle reseller commission

        app()->end();
    }

}
