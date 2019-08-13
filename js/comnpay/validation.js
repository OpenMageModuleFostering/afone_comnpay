Validation.addAllThese([
        ['validate-no-tpe', "Veuillez saisir un num&eacute;ro de TPE valide. Exemple : HOM-XXX-XXX ou VAD-XXX-XXX (en production)", function (v) {
            if(document.getElementById("payment_comnpay_debit_url_gateway_config").value == "HOMOLOGATION")
                return Validation.get('IsEmpty').test(v) || /^HOM-[a-z0-9]{3}-[a-z0-9]{3}|DEMO*$/i.test(v);
            else if(document.getElementById("payment_comnpay_debit_url_gateway_config").value == "PRODUCTION")
                return Validation.get('IsEmpty').test(v) ||  /^VAD-[a-z0-9]{3}-[a-z0-9]{3}$/i.test(v);
        }],
        ['validate-secret-key',"Veuillez saisir une cl&eacute; secr&egrave;te valide", function (v) {
            if(document.getElementById("payment_comnpay_debit_tpe_no").value == "DEMO") {
                if (v == "DEMO")
                    return true;
                else
                    return false;
            }
            else {
                if(Validation.get('IsEmpty').test(v))
                    return false;
                else
                    return true;
            }
        }]
    ]);
