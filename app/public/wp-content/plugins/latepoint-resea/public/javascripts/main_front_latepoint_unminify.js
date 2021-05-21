"use strict";

function latepoint_is_timeframe_in_periods(e, t, a, n) {
    for (var o = arguments.length > 3 && void 0 !== n && n, s = 0; s < a.length; s++) {
        var i = 0,
            l = 0,
            r = 0,
            c = 0,
            d = a[s].split(":");
        if (2 == d.length ? (i = d[0], l = d[1]) : (r = d[2], c = d[3], i = parseFloat(d[0]) - parseFloat(r), l = parseFloat(d[1]) + parseFloat(c)), o) {
            if (latepoint_is_period_inside_another(e, t, i, l)) return !0
        } else if (latepoint_is_period_overlapping(e, t, i, l)) return !0
    }
    return !1
}

function latepoint_is_period_overlapping(e, t, a, n) {
    return e < n && a < t
}

function latepoint_is_period_inside_another(e, t, a, n) {
    return e >= a && t <= n
}

function latepoint_minutes_to_hours_preferably(e) {
    var t = latepoint_is_army_clock(),
        a = Math.floor(e / 60);
    !t && a > 12 && (a -= 12);
    var n = e % 60;
    return n > 0 && (a = a + ":" + n), a
}

function latepoint_minutes_to_hours(e) {
    var t = latepoint_is_army_clock(),
        a = Math.floor(e / 60);
    return !t && a > 12 && (a -= 12), a
}

function latepoint_am_or_pm(e) {
    return latepoint_is_army_clock() ? "" : e < 720 ? "am" : "pm"
}

function latepoint_hours_and_minutes_to_minutes(e, t) {
    var a = e.split(":"),
        n = a[0],
        o = a[1];
    return "pm" == t && n < 12 && (n = parseInt(n) + 12), "am" == t && 12 == n && (n = 0), o = parseInt(o) + 60 * n
}

function latepoint_get_time_system() {
    return latepoint_helper.time_system
}

function latepoint_is_army_clock() {
    return "24" == latepoint_get_time_system()
}

function latepoint_minutes_to_hours_and_minutes(e, t) {
    var a = latepoint_is_army_clock(),
        n = arguments.length > 1 && void 0 !== t ? t : "%02d:%02d",
        o = Math.floor(e / 60),
        s;
    return !a && o > 12 && (o -= 12), sprintf(n, o, e % 60)
}

function latepoint_mask_timefield(e) {
    jQuery().inputmask && e.inputmask({
        mask: "99:99",
        placeholder: "HH:MM"
    })
}

function latepoint_mask_phone(e) {
    latepoint_is_phone_masking_enabled() && jQuery().inputmask && e.inputmask(latepoint_get_phone_format())
}

function latepoint_get_phone_format() {
    return latepoint_helper.phone_format
}

function latepoint_is_phone_masking_enabled() {
    return "yes" == latepoint_helper.enable_phone_masking
}

function latepoint_show_booking_end_time() {
    return "yes" == latepoint_helper.show_booking_end_time
}

function latepoint_init_form_masks() {
    latepoint_is_phone_masking_enabled() && latepoint_mask_phone(jQuery(".os-mask-phone"))
}

function latepoint_get_paypal_payment_amount(e) {
    var t, a;
    return "deposit" == e.find('input[name="booking[payment_portion]"]').val() ? e.find(".lp-paypal-btn-trigger").data("deposit-amount") : e.find(".lp-paypal-btn-trigger").data("full-amount")
}

function latepoint_add_notification(e, t) {
    var a = arguments.length > 1 && void 0 !== t ? t : "success",
        n = jQuery("body").find(".os-notifications");
    n.length || (jQuery("body").append('<div class="os-notifications"></div>'), n = jQuery("body").find(".os-notifications")), n.find(".item").length > 0 && n.find(".item:first-child").remove(), n.append('<div class="item item-type-' + a + '">' + e + '<span class="os-notification-close"><i class="latepoint-icon latepoint-icon-x"></i></span></div>')
}

function latepoint_generate_form_message_html(e, t) {
    var a = '<div class="os-form-message-w status-' + t + '"><ul>';
    return Array.isArray(e) ? e.forEach(function(e) {
        a += "<li>" + e + "</li>"
    }) : a += "<li>" + e + "</li>", a += "</ul></div>"
}

function latepoint_clear_form_messages(e) {
    e.find(".os-form-message-w").remove()
}

function latepoint_show_data_in_lightbox(e, t) {
    var a = arguments.length > 1 && void 0 !== t ? t : "";
    jQuery(".latepoint-lightbox-w").remove();
    var n = "latepoint-lightbox-w latepoint-w ";
    a && (n += a), jQuery("body").append('<div class="' + n + '"><div class="latepoint-lightbox-i">' + e + '<a href="#" class="latepoint-lightbox-close"><i class="latepoint-icon latepoint-icon-x"></i></a></div><div class="latepoint-lightbox-shadow"></div></div>'), jQuery("body").addClass("latepoint-lightbox-active")
}

function _classCallCheck(e, t) {
    if (!(e instanceof t)) throw new TypeError("Cannot call a class as a function")
}

function _defineProperties(e, t) {
    for (var a = 0; a < t.length; a++) {
        var n = t[a];
        n.enumerable = n.enumerable || !1, n.configurable = !0, "value" in n && (n.writable = !0), Object.defineProperty(e, n.key, n)
    }
}

function _createClass(e, t, a) {
    return t && _defineProperties(e.prototype, t), a && _defineProperties(e, a), e
}

function _classCallCheck(e, t) {
    if (!(e instanceof t)) throw new TypeError("Cannot call a class as a function")
}

function _defineProperties(e, t) {
    for (var a = 0; a < t.length; a++) {
        var n = t[a];
        n.enumerable = n.enumerable || !1, n.configurable = !0, "value" in n && (n.writable = !0), Object.defineProperty(e, n.key, n)
    }
}

function _createClass(e, t, a) {
    return t && _defineProperties(e.prototype, t), a && _defineProperties(e, a), e
}

function latepoint_apply_coupon(e) {
    var t = e.closest(".latepoint-booking-form-element"),
        a = e;
    a.closest(".coupon-code-input-w").addClass("os-loading");
    var n = t.find(".latepoint-form").serialize(),
        o = {
            action: "latepoint_route_call",
            route_name: e.data("route"),
            params: n,
            layout: "none",
            return_format: "json"
        };
    jQuery.ajax({
        type: "post",
        dataType: "json",
        url: latepoint_helper.ajaxurl,
        data: o,
        success: function e(n) {
            a.closest(".coupon-code-input-w").removeClass("os-loading"), "success" === n.status ? (latepoint_show_message_inside_element(n.message, t.find(".latepoint-body"), "success"), latepoint_reload_step(t)) : latepoint_show_message_inside_element(n.message, t.find(".latepoint-body"), "error")
        }
    })
}

function latepoint_coupon_removed(e) {
    var t;
    e.closest(".applied-coupon-code").fadeOut(), latepoint_reload_step(e.closest(".latepoint-booking-form-element"))
}

function latepoint_reload_step(e) {
    return e.find(".latepoint_step_direction").val("specific"), e.find(".latepoint-form").submit(), !1
}

function latepoint_get_new_prev_payment_class(e, t) {
    var a = "";
    switch (t) {
        case "lp-show-pay-times":
            a = "";
            break;
        case "lp-show-pay-methods":
            a = e.find(".lp-payment-times-w").length ? "lp-show-pay-times" : latepoint_get_new_prev_payment_class(e, "lp-show-pay-times");
            break;
        case "lp-show-pay-portion-selection":
            a = e.find(".lp-payment-methods-w").length ? "lp-show-pay-methods" : latepoint_get_new_prev_payment_class(e, "lp-show-pay-methods");
            break;
        case "lp-show-card":
        case "lp-show-paypal":
            a = e.find(".lp-payment-portion-selection-w").length ? "lp-show-pay-portion-selection" : latepoint_get_new_prev_payment_class(e, "lp-show-pay-portion-selection");
            break
    }
    return a
}

function latepoint_reset_password_from_booking_init() {
    jQuery(".os-step-existing-customer-login-w").hide(), jQuery(".os-password-reset-form-holder").on("click", ".password-reset-back-to-login", function() {
        return jQuery(".os-password-reset-form-holder").html(""), jQuery(".os-step-existing-customer-login-w").show(), !1
    })
}

function latepoint_update_summary_field(e, t, a) {
    var n = e.closest(".latepoint-with-summary");
    n.length && ((a = String(a).trim()) ? (e.find(".os-summary-value-" + t).text(a).closest(".os-summary-line").addClass("os-has-value"), n.hasClass("latepoint-summary-is-open") ? e.find(".os-summary-line.os-has-value").slideDown(150) : e.find(".os-summary-line.os-has-value").fadeIn(200), n.addClass("latepoint-summary-is-open")) : e.find(".os-summary-value-" + t).text("").closest(".os-summary-line").slideUp(150).removeClass("os-has-value"))
}

function latepoint_password_changed_show_login(e) {
    jQuery(".os-step-existing-customer-login-w").show(), jQuery(".os-password-reset-form-holder").html(""), latepoint_show_message_inside_element(e.message, jQuery(".os-step-existing-customer-login-w"), "success")
}

function latepoint_hide_message_inside_element(e) {
    var t = arguments.length > 0 && void 0 !== e ? e : jQuery(".latepoint-body");
    t.length && t.find(".latepoint-message").length && t.find(".latepoint-message").remove()
}

function latepoint_show_message_inside_element(e, t, a) {
    var n = arguments.length > 1 && void 0 !== t ? t : jQuery(".latepoint-body"),
        o = arguments.length > 2 && void 0 !== a ? a : "error";
    n.length && (n.find(".latepoint-message").length ? n.find(".latepoint-message").removeClass("latepoint-message-success").removeClass("latepoint-message-error").addClass("latepoint-message-" + o).html(e).show() : n.prepend('<div class="latepoint-message latepoint-message-' + o + '">' + e + "</div>"))
}

function latepoint_clear_step_vars(e, t) {
    switch (e) {
        case "locations":
            t.find('input[name="booking[start_date]"]').val("");
            break;
        case "services":
            t.find('input[name="booking[service_id]"]').val("");
            break;
        case "agents":
            t.find('input[name="booking[agent_id]"]').val("");
            break;
        case "datepicker":
            t.find('input[name="booking[start_date]"]').val(""), t.find('input[name="booking[start_time]"]').val("");
            break
    }
}

function latepoint_set_payment_token_and_submit(e, t) {
    var a;
    e.find('input[name="booking[payment_token]"]').val(t), e.find(".latepoint-form").find(".latepoint_step_direction").val("next").submit()
}
jQuery(document).ready(function(e) {
    e(".latepoint").on("click", "button[data-os-action], a[data-os-action], div[data-os-action], span[data-os-action]", function(t) {
        var a = e(this);
        if (a.data("os-prompt") && !confirm(a.data("os-prompt"))) return !1;
        var n = e(this).data("os-params");
        e(this).data("os-source-of-params") && (n = e(e(this).data("os-source-of-params")).find("select, input, textarea").serialize());
        var o = a.data("os-return-format") ? a.data("os-return-format") : "json",
            s = {
                action: "latepoint_route_call",
                route_name: e(this).data("os-action"),
                params: n,
                return_format: o
            };
        return a.addClass("os-loading"), e.ajax({
            type: "post",
            dataType: "json",
            url: latepoint_helper.ajaxurl,
            data: s,
            success: function t(n) {
                if ("success" === n.status) {
                    if ("lightbox" == a.data("os-output-target")) latepoint_show_data_in_lightbox(n.message, a.data("os-lightbox-classes"));
                    else if ("side-panel" == a.data("os-output-target")) e(".latepoint-side-panel-w").remove(), e("body").append('<div class="latepoint-side-panel-w"><div class="latepoint-side-panel-i">' + n.message + '</div><div class="latepoint-side-panel-shadow"></div></div>');
                    else {
                        if ("reload" == a.data("os-success-action")) return latepoint_add_notification(n.message), void location.reload();
                        if ("redirect" == a.data("os-success-action")) return void(a.data("os-redirect-to") ? (latepoint_add_notification(n.message), window.location.replace(a.data("os-redirect-to"))) : window.location.replace(n.message));
                        a.data("os-output-target") && e(a.data("os-output-target")).length ? "append" == a.data("os-output-target-do") ? e(a.data("os-output-target")).append(n.message) : e(a.data("os-output-target")).html(n.message) : "before" == a.data("os-before-after") ? a.before(n.message) : "before" == a.data("os-before-after") ? a.after(n.message) : latepoint_add_notification(n.message)
                    }
                    if (a.data("os-after-call")) {
                        var o = a.data("os-after-call");
                        a.data("os-pass-this") ? window[o](a) : a.data("os-pass-response") ? window[o](n) : window[o]()
                    }
                    a.removeClass("os-loading")
                } else a.removeClass("os-loading"), a.data("os-output-target") && e(a.data("os-output-target")).length ? e(a.data("os-output-target")).prepend(latepoint_generate_form_message_html(n.message, "error")) : alert(n.message)
            }
        }), !1
    }), e(".latepoint").on("click", 'form[data-os-action] button[type="submit"]', function(t) {
        e(this).addClass("os-loading")
    }), e(".latepoint").on("submit", "form[data-os-action]", function(t) {
        t.preventDefault();
        var a = e(this),
            n = a.serialize(),
            o = {
                action: "latepoint_route_call",
                route_name: e(this).data("os-action"),
                params: n,
                return_format: "json"
            };
        return a.find('button[type="submit"]').addClass("os-loading"), e.ajax({
            type: "post",
            dataType: "json",
            url: latepoint_helper.ajaxurl,
            data: o,
            success: function t(n) {
                if (a.find('button[type="submit"].os-loading').removeClass("os-loading"), latepoint_clear_form_messages(a), "success" === n.status) {
                    if ("reload" == a.data("os-success-action")) return latepoint_add_notification(n.message), void location.reload();
                    if ("redirect" == a.data("os-success-action")) return void(a.data("os-redirect-to") ? (latepoint_add_notification(n.message), window.location.replace(a.data("os-redirect-to"))) : window.location.replace(n.message));
                    if (a.data("os-output-target") && e(a.data("os-output-target")).length ? e(a.data("os-output-target")).html(n.message) : "redirect" == n.message ? window.location.replace(n.url) : (latepoint_add_notification(n.message), a.prepend(latepoint_generate_form_message_html(n.message, "success"))), a.data("os-record-id-holder") && n.record_id && a.find('[name="' + a.data("os-record-id-holder") + '"]').val(n.record_id), a.data("os-after-call")) {
                        var o = a.data("os-after-call");
                        a.data("os-pass-response") ? window[o](n) : window[o]()
                    }
                    n.form_values_to_update && e.each(n.form_values_to_update, function(e, t) {
                        a.find('[name="' + e + '"]').val(t)
                    }), e("button.os-loading").removeClass("os-loading")
                } else e("button.os-loading").removeClass("os-loading"), a.data("os-show-errors-as-notification") ? latepoint_add_notification(n.message, "error") : (a.prepend(latepoint_generate_form_message_html(n.message, "error")), e([document.documentElement, document.body]).animate({
                    scrollTop: a.find(".os-form-message-w").offset().top - 30
                }, 200))
            }
        }), !1
    })
});
var OsPaymentsBraintree = function() {
        function e() {
            _classCallCheck(this, e)
        }
        return _createClass(e, null, [{
            key: "create_paypal_button",
            value: function e(t) {
                var a = jQuery(t).closest(".latepoint-booking-form-element"),
                    n = latepoint_get_paypal_payment_amount(a);
                braintree.client.create({
                    authorization: latepoint_helper.braintree_paypal_client_auth
                }).then(function(e) {
                    return braintree.paypalCheckout.create({
                        client: e
                    })
                }).then(function(e) {
                    return paypal.Button.render({
                        style: {
                            label: "pay",
                            size: "large",
                            shape: "rect",
                            tagline: !1,
                            color: "gold"
                        },
                        env: latepoint_helper.braintree_paypal_environment_name,
                        payment: function t() {
                            return e.createPayment({
                                flow: "checkout",
                                amount: n,
                                currency: latepoint_helper.paypal_payment_currency,
                                intent: "authorize"
                            })
                        },
                        onAuthorize: function t(n, o) {
                            return e.tokenizePayment(n).then(function(e) {
                                latepoint_set_payment_token_and_submit(a, e.nonce)
                            })
                        },
                        onCancel: function e(t) {},
                        onError: function e(t) {
                            console.error("checkout.js error", t)
                        }
                    }, t)
                }).then(function() {}).catch(function(e) {
                    console.error("Error!", e)
                })
            }
        }, {
            key: "create_token",
            value: function t(a) {
                var n = jQuery(a).find("#payment_name_on_card"),
                    o = jQuery(a).find("#payment_zip"),
                    s = n ? n.value : void 0,
                    i = o ? o.value : void 0;
                e.hostedFieldsInstance.tokenize(function(e, t) {
                    e ? ("HOSTED_FIELDS_FIELDS_INVALID" == e.code && e.details.invalidFields && jQuery.each(e.details.invalidFields, function(e, t) {
                        jQuery(t).addClass("braintree-hosted-fields-invalid")
                    }), latepoint_show_message_inside_element(e.message), jQuery(a).find(".latepoint-next-btn").removeClass("os-loading")) : latepoint_set_payment_token_and_submit(a, t.nonce)
                })
            }
        }, {
            key: "init_cc_form",
            value: function t() {
                braintree.client.create({
                    authorization: latepoint_helper.braintree_tokenization_key
                }, function(t, a) {
                    t ? console.error(t) : braintree.hostedFields.create({
                        client: a,
                        styles: {
                            input: {
                                "font-size": "14px",
                                "font-family": latepoint_helper.body_font_family,
                                "font-weight": "500",
                                color: "#fff"
                            },
                            ":focus": {
                                color: "#fff"
                            },
                            "::placeholder": {
                                color: "#7d89b1"
                            },
                            ".valid": {
                                color: "#fff"
                            },
                            ".invalid": {
                                color: "#ff5a16"
                            }
                        },
                        fields: {
                            number: {
                                selector: "#payment_card_number",
                                placeholder: jQuery("#payment_card_number").data("placeholder")
                            },
                            cvv: {
                                selector: "#payment_card_cvc",
                                placeholder: jQuery("#payment_card_cvc").data("placeholder")
                            },
                            expirationDate: {
                                selector: "#payment_card_expiration",
                                placeholder: jQuery("#payment_card_expiration").data("placeholder")
                            }
                        }
                    }, function(t, a) {
                        e.hostedFieldsInstance = a, t && console.error(t)
                    })
                })
            }
        }]), e
    }(),
    OsPaymentsPaypal = function() {
        function e() {
            _classCallCheck(this, e)
        }
        return _createClass(e, null, [{
            key: "create_paypal_button",
            value: function e(t) {
                var a = jQuery(t).closest(".latepoint-booking-form-element"),
                    n = latepoint_get_paypal_payment_amount(a);
                paypal.Buttons({
                    createOrder: function e(t, a) {
                        return a.order.create({
                            purchase_units: [{
                                amount: {
                                    value: n,
                                    currency_code: latepoint_helper.paypal_payment_currency
                                }
                            }],
                            application_context: {
                                shipping_preference: "NO_SHIPPING"
                            }
                        })
                    },
                    onApprove: function e(t, n) {
                        jQuery(a).removeClass("step-content-loaded").addClass("step-content-loading"), n.order.authorize().then(function(e) {
                            var n = e.purchase_units[0].payments.authorizations[0].id,
                                o = t.orderID;
                            latepoint_set_payment_token_and_submit(a, n)
                        })
                    }
                }).render(t)
            }
        }]), e
    }();
! function(e) {
    function t(e) {
        e.forEach(function(e) {
            e.on("change", function(e) {
                e.error ? latepoint_show_message_inside_element(e.error.message) : latepoint_hide_message_inside_element()
            })
        })
    }

    function a(e) {
        var t = jQuery(e).find("#payment_name_on_card"),
            a = jQuery(e).find("#payment_zip"),
            n = {
                name: t ? t.value : void 0,
                address_zip: a ? a.value : void 0
            };
        q.createToken(W, n).then(function(t) {
            t.token ? latepoint_set_payment_token_and_submit(e, t.token.id) : (latepoint_show_message_inside_element(t.error.message), jQuery(e).find(".latepoint-next-btn").removeClass("os-loading"))
        })
    }

    function n() {
        q = Stripe(latepoint_helper.stripe_key), L = q.elements();
        var e = {
                base: {
                    fontFamily: latepoint_helper.body_font_family,
                    fontSize: "14px",
                    fontWeight: 500,
                    color: "#ffffff",
                    "::placeholder": {
                        color: "#7d89b1"
                    }
                }
            },
            a = {
                focus: "focused",
                empty: "empty",
                invalid: "invalid"
            };
        (W = L.create("cardNumber", {
            style: e,
            classes: a,
            placeholder: jQuery("#payment_card_number").data("placeholder")
        })).mount("#payment_card_number"), (G = L.create("cardExpiry", {
            style: e,
            classes: a,
            placeholder: jQuery("#payment_card_expiration").data("placeholder")
        })).mount("#payment_card_expiration"), (U = L.create("cardCvc", {
            style: e,
            classes: a,
            placeholder: jQuery("#payment_card_cvc").data("placeholder")
        })).mount("#payment_card_cvc"), t([W, G, U])
    }

    function o(e, t) {
        var a = arguments.length > 1 && void 0 !== t && t;
        switch (y(), b(), e) {
            case "datepicker":
                _();
                break;
            case "contact":
                F();
                break;
            case "agents":
                T();
                break;
            case "locations":
                break;
            case "services":
                j();
                break;
            case "payment":
                v(a);
                break;
            case "verify":
                g(a);
                break;
            case "confirmation":
                z();
                break
        }
    }

    function s(e) {
        e.find(".latepoint-next-btn").removeClass("disabled"), e.removeClass("hidden-buttons")
    }

    function i(e) {
        e.find(".latepoint-next-btn").addClass("disabled"), e.find(".latepoint-prev-btn.disabled").length && e.addClass("hidden-buttons")
    }

    function l(e) {
        e.find(".latepoint-prev-btn").removeClass("disabled"), e.removeClass("hidden-buttons")
    }

    function r(e) {
        e.find(".latepoint-prev-btn").addClass("disabled"), e.find(".latepoint-next-btn.disabled").length && e.addClass("hidden-buttons")
    }

    function c(e) {
        var t = e.closest(".latepoint-booking-form-element");
        e.addClass("selected");
        var a = e.data("service-duration"),
            n = e.data("interval"),
            o = e.data("work-start-time"),
            s = e.data("work-end-time"),
            i = e.data("total-work-minutes"),
            l = !!e.data("available-minutes") && e.data("available-minutes").toString().split(",").map(Number),
            r = e.data("day-minutes").toString().split(",").map(Number),
            c = t.find(".timeslots");
        if (c.html(""), i > 0 && l.length && r.length) {
            var d = !1;
            r.forEach(function(e) {
                if (!(e + a > s)) {
                    var t = latepoint_am_or_pm(e);
                    if (!1 !== d && e - d > a) {
                        var o = latepoint_minutes_to_hours_and_minutes(d + a) + " " + latepoint_am_or_pm(d + a) + " - " + latepoint_minutes_to_hours_and_minutes(e) + " " + latepoint_am_or_pm(e),
                            r = (e - d - a) / (i + a) * 100;
                        c.append('<div class="dp-timeslot is-off" style="width:' + r + '%"><span class="dp-label">' + o + "</span></div>")
                    }
                    var p = "dp-timeslot";
                    l.includes(e) || (p += " is-booked");
                    var m = "";
                    (e % 60 == 0 || n >= 60) && (p += " with-tick", m = '<span class="dp-tick"><strong>' + latepoint_minutes_to_hours_preferably(e) + "</strong> " + t + "</span>");
                    var u = latepoint_minutes_to_hours_and_minutes(e) + " " + t;
                    if (latepoint_show_booking_end_time()) {
                        var _ = e + a,
                            f = latepoint_am_or_pm(_);
                        u += " - " + latepoint_minutes_to_hours_and_minutes(_) + " " + f
                    }
                    u = u.trim(), c.append('<div class="' + p + '" data-minutes="' + e + '"><span class="dp-label">' + u + "</span>" + m + "</div>"), d = e
                }
            })
        } else c.append('<div class="not-working-message">' + latepoint_helper.msg_not_available + "</div>")
    }

    function d() {
        e(".dp-timeslot").on("click", function() {
            var t = e(this).closest(".latepoint-booking-form-element");
            if (e(this).hasClass("is-booked") || e(this).hasClass("is-off"));
            else if (e(this).hasClass("selected")) e(this).removeClass("selected"), e(this).find(".dp-success-label").remove(), t.find(".latepoint_start_time").val(""), i(t), latepoint_update_summary_field(t, "time", "");
            else {
                t.find(".dp-timeslot.selected").removeClass("selected").find(".dp-success-label").remove();
                var a = e(this).find(".dp-label").html();
                e(this).addClass("selected").find(".dp-label").html('<span class="dp-success-label">' + t.find(".latepoint-form").data("selected-label") + "</span>" + a), t.find(".latepoint_start_time").val(e(this).data("minutes")), s(t), latepoint_update_summary_field(t, "time", a)
            }
            return !1
        })
    }

    function p() {
        e(".os-month-next-btn").on("click", function() {
            var t = e(this).closest(".latepoint-booking-form-element"),
                a = e(this).data("route");
            if (t.find(".os-monthly-calendar-days-w.active + .os-monthly-calendar-days-w").length) t.find(".os-monthly-calendar-days-w.active").removeClass("active").next(".os-monthly-calendar-days-w").addClass("active"), m(t);
            else if (1) {
                var n = e(this);
                n.addClass("os-loading");
                var o = t.find(".os-monthly-calendar-days-w").last(),
                    s = o.data("calendar-year"),
                    i = o.data("calendar-month");
                12 == i ? (s += 1, i = 1) : i += 1;
                var l, r = {
                    action: "latepoint_route_call",
                    route_name: a,
                    params: {
                        target_date_string: s + "-" + i + "-1",
                        location_id: t.find(".latepoint_location_id").val(),
                        agent_id: t.find(".latepoint_agent_id").val(),
                        service_id: t.find(".latepoint_service_id").val()
                    },
                    layout: "none",
                    return_format: "json"
                };
                e.ajax({
                    type: "post",
                    dataType: "json",
                    url: latepoint_helper.ajaxurl,
                    data: r,
                    success: function e(a) {
                        n.removeClass("os-loading"), "success" === a.status && (t.find(".os-months").append(a.message), t.find(".os-monthly-calendar-days-w.active").removeClass("active").next(".os-monthly-calendar-days-w").addClass("active"), m(t))
                    }
                })
            }
            return u(t), !1
        }), e(".os-month-prev-btn").on("click", function() {
            var t = e(this).closest(".latepoint-booking-form-element");
            return t.find(".os-monthly-calendar-days-w.active").prev(".os-monthly-calendar-days-w").length && (t.find(".os-monthly-calendar-days-w.active").removeClass("active").prev(".os-monthly-calendar-days-w").addClass("active"), m(t)), u(t), !1
        })
    }

    function m(e) {
        e.find(".os-current-month-label").text(e.find(".os-monthly-calendar-days-w.active").data("calendar-month-label"))
    }

    function u(e) {
        e.find(".os-current-month-label").html(e.find(".os-monthly-calendar-days-w.active .os-monthly-calendar-days").data("calendar-month-label")), e.find(".os-monthly-calendar-days-w.active").prev(".os-monthly-calendar-days-w").length ? e.find(".os-month-prev-btn").removeClass("disabled") : e.find(".os-month-prev-btn").addClass("disabled")
    }

    function _() {
        d(), p(), e(".os-months").on("click", ".os-day", function() {
            if (e(this).hasClass("os-day-passed")) return !1;
            if (e(this).hasClass("os-not-in-allowed-period")) return !1;
            var t = e(this).closest(".latepoint-booking-form-element");
            return t.find(".os-day.selected").removeClass("selected"), c(e(this)), d(), e(".times-header span").text(e(this).data("nice-date")), t.find(".time-selector-w").slideDown(200, function() {
                var e = t.find(".latepoint-body");
                e.stop().animate({
                    scrollTop: e[0].scrollHeight
                }, 200)
            }), t.find(".latepoint_start_date").val(e(this).data("date")), latepoint_update_summary_field(t, "date", e(this).data("nice-date")), t.find(".latepoint_start_time").val(""), i(t), !1
        })
    }

    function f(t) {
        t.find(".lp-paypal-btn-trigger").html(""), latepoint_helper.is_braintree_paypal_active && OsPaymentsBraintree.create_paypal_button(t.find(".lp-paypal-btn-trigger")[0]), latepoint_helper.is_paypal_native_active && OsPaymentsPaypal.create_paypal_button(t.find(".lp-paypal-btn-trigger")[0]), e(".lp-paypal-demo-mode-trigger").on("click", function() {
            e(this).closest(".latepoint-form").submit()
        })
    }

    function h(t) {
        var a = arguments.length > 0 && void 0 !== t && t;
        if (a) {
            if (!a.find(".lp-card-w").length) return
        } else if (!e(".lp-card-w").length) return;
        latepoint_helper.is_braintree_active && OsPaymentsBraintree.init_cc_form(), latepoint_helper.is_stripe_active && n()
    }

    function g(e) {
        var t = arguments.length > 0 && void 0 !== e && e;
        t && t.closest(".latepoint-summary-is-open").removeClass("latepoint-summary-is-open")
    }

    function v(t) {
        var a = arguments.length > 0 && void 0 !== t && t;
        a.find(".step-payment-w").data("full-amount") > 0 && latepoint_update_summary_field(a, "price", x(a.find(".step-payment-w").data("full-amount"))), a && "deposit" == a.find(".step-payment-w").data("default-portion") && a.find('input[name="booking[payment_portion]"]').val("deposit"), h(a), a && "lp-show-paypal" == a.find(".step-payment-w").data("current-payment-step") && (f(a), i(a)), e(".latepoint-booking-form-element .coupon-code-input-submit").on("click", function(t) {
            return latepoint_apply_coupon(e(this).closest(".coupon-code-input-w").find(".coupon-code-input")), !1
        }), e(".latepoint-booking-form-element input.coupon-code-input").on("keyup", function(t) {
            if (13 === t.which) return latepoint_apply_coupon(e(this)), !1
        }), e(".latepoint-booking-form-element .coupon-code-trigger-w a").on("click", function(t) {
            return e(this).closest(".payment-total-info").addClass("entering-coupon").find(".coupon-code-input").focus(), !1
        }), e(".latepoint-booking-form-element .lp-payment-trigger-locally").on("click", function(t) {
            var a = e(this).closest(".latepoint-booking-form-element");
            a.find('input[name="booking[payment_method]"]').val(e(this).data("method")), a.find('input[name="booking[payment_portion]"]').val(""), s(a)
        }), e(".latepoint-booking-form-element .lp-payment-trigger-method-selector").on("click", function(t) {
            var a = e(this).closest(".latepoint-booking-form-element"),
                n = a.find(".step-payment-w"),
                o = n.data("current-payment-step"),
                s = "lp-show-pay-methods";
            return n.removeClass(o).addClass("lp-show-pay-methods").data("current-payment-step", "lp-show-pay-methods").data("prev-payment-step", o), i(a), l(a), !1
        }), e(".latepoint-booking-form-element .lp-payment-trigger-cc").on("click", function(t) {
            var a = e(this).closest(".latepoint-booking-form-element");
            a.find('input[name="booking[payment_method]"]').val(e(this).data("method"));
            var n = a.find(".step-payment-w"),
                o = n.data("current-payment-step"),
                r = "lp-show-pay-portion-selection";
            a.find(".lp-payment-portion-selection-w").length ? i(a) : (r = "lp-show-card", s(a)), l(a), n.removeClass(o).addClass(r).data("current-payment-step", r).data("prev-payment-step", o)
        }), e(".latepoint-booking-form-element .lp-payment-trigger-paypal").on("click", function(t) {
            var a = e(this).closest(".latepoint-booking-form-element");
            a.find('input[name="booking[payment_method]"]').val(e(this).data("method"));
            var n = a.find(".step-payment-w"),
                o = n.data("current-payment-step"),
                s = "lp-show-pay-portion-selection";
            a.find(".lp-payment-portion-selection-w").length || (f(a), s = "lp-show-paypal"), n.removeClass(o).addClass(s).data("current-payment-step", s).data("prev-payment-step", o), i(a), l(a)
        }), e(".latepoint-booking-form-element .lp-trigger-payment-portion-selector").on("click", function(t) {
            var a = e(this).closest(".latepoint-booking-form-element"),
                n = jQuery(this).data("portion");
            a.find('input[name="booking[payment_portion]"]').val(n), "deposit" == n ? a.find(".payment-total-info").addClass("paying-deposit") : a.find(".payment-total-info").removeClass("paying-deposit");
            var o = a.find(".step-payment-w"),
                r = o.data("current-payment-step"),
                c = "lp-show-card";
            "card" == a.find('input[name="booking[payment_method]"]').val() ? s(a) : (f(a), c = "lp-show-paypal", i(a)), l(a), o.removeClass(r).addClass(c).data("current-payment-step", c).data("prev-payment-step", r)
        })
    }

    function y() {
        e(".os-selectable-items .os-selectable-item").off("click", C), e(".os-selectable-items .os-selectable-item").on("click", C)
    }

    function b() {
        e(".os-selectable-items .os-priced-item").off("click", w), e(".os-selectable-items .os-priced-item").on("click", w)
    }

    function w() {
        var t = e(this).closest(".latepoint-booking-form-element"),
            a = e(this).hasClass("selected") ? "+" : "-";
        return k(e(this).data("item-price"), t, a), !1
    }

    function k(e, t, a) {
        var n = 100 * Number(t.find(".latepoint_total_price").val());
        e = 100 * Number(e), n = "+" == a ? n + e : n - e, n /= 100, t.find(".latepoint_total_price").val(n), latepoint_update_summary_field(t, "price", n > 0 ? n = x(n) : "")
    }

    function C() {
        var t = e(this).closest(".latepoint-booking-form-element"),
            a = "";
        if (e(this).hasClass("os-allow-multiselect")) {
            e(this).toggleClass("selected");
            var n = e(this).closest(".os-selectable-items").find(".os-selectable-item.selected").map(function() {
                return e(this).data("item-id")
            }).get();
            t.find(e(this).data("id-holder")).val(n), a = String(e(this).closest(".os-selectable-items").find(".os-selectable-item.selected").map(function() {
                return " " + e(this).data("summary-value")
            }).get()).trim()
        } else e(this).closest(".os-selectable-items").find(".os-selectable-item.selected").removeClass("selected"), e(this).addClass("selected"), t.find(e(this).data("id-holder")).val(e(this).data("item-id")), a = e(this).data("summary-value");
        return latepoint_update_summary_field(t, e(this).data("summary-field-name"), a), s(t), !1
    }

    function x(e) {
        return latepoint_helper.currency_symbol_before + String(e) + latepoint_helper.currency_symbol_after
    }

    function j() {
        e(".os-service-category-info").on("click", function() {
            var t;
            l(e(this).closest(".latepoint-booking-form-element")), e(this).closest(".step-services-w").addClass("selecting-service-category");
            var a = e(this).closest(".os-service-category-w"),
                n = e(this).closest(".os-service-categories-main-parent");
            return a.hasClass("selected") ? (a.removeClass("selected"), a.parent().closest(".os-service-category-w").length ? a.parent().closest(".os-service-category-w").addClass("selected") : n.removeClass("show-selected-only")) : (n.find(".os-service-category-w.selected").removeClass("selected"), n.addClass("show-selected-only"), a.addClass("selected")), !1
        }), e(".os-services .os-service-duration-selector").on("click", function() {
            var t = e(this).closest(".latepoint-booking-form-element"),
                a;
            return e(this).closest(".os-items").find(".os-item.selected").removeClass("selected"), e(this).closest(".os-item").addClass("selected"), t.find(".latepoint_duration").val(e(this).data("duration")), s(t), latepoint_update_summary_field(t, "duration", e(this).find(".os-duration-value").text() + " " + e(this).find(".os-duration-label").text()), !1
        }), e(".os-services .os-service-selector").on("click", function() {
            var t = e(this).closest(".latepoint-booking-form-element");
            return e(this).closest(".os-items").find(".os-item.selected").removeClass("selected"), e(this).closest(".os-item").addClass("selected"), t.find(".latepoint_service_id").val(e(this).data("service-id")), t.find(".latepoint_duration").val(""), e(this).closest(".os-item").hasClass("has-multiple-durations") ? (e(this).closest(".step-services-w").addClass("selecting-service-duration"), l(t), i(t)) : s(t), latepoint_update_summary_field(t, "service", e(this).find(".os-item-name").text()), latepoint_update_summary_field(t, "duration", ""), !1
        })
    }

    function Q() {}

    function T() {
        e(".os-items .os-item-details-btn").on("click", function() {
            var t = e(this).closest(".latepoint-booking-form-element"),
                a = e(this).data("agent-id");
            return t.find(".os-agent-bio-popup.active").removeClass("active"), t.find("#osAgentBioPopup" + a).addClass("active"), !1
        }), e(".os-agent-bio-close").on("click", function() {
            return e(this).closest(".os-agent-bio-popup").removeClass("active"), !1
        })
    }

    function z() {
        e(".latepoint-booking-form-element").on("click", ".set-customer-password-btn", function() {
            var t = e(this),
                a = e(this).closest(".latepoint-booking-form-element");
            t.addClass("os-loading");
            var n = {
                    account_nonse: e('input[name="account_nonse"]').val(),
                    password: e('input[name="customer[password]"]').val(),
                    password_confirmation: e('input[name="customer[password_confirmation]"]').val()
                },
                o = {
                    action: "latepoint_route_call",
                    route_name: e(this).data("btn-action"),
                    params: e.param(n),
                    layout: "none",
                    return_format: "json"
                };
            e.ajax({
                type: "post",
                dataType: "json",
                url: latepoint_helper.ajaxurl,
                data: o,
                success: function e(n) {
                    t.removeClass("os-loading"), "success" === n.status ? a.find(".step-confirmation-set-password").html(latepoint_generate_form_message_html(n.message, "success")) : latepoint_show_message_inside_element(n.message, a.find(".step-confirmation-set-password"), "error")
                }
            })
        }), e(".latepoint-booking-form-element").on("click", ".show-set-password-fields", function() {
            var t;
            return e(this).closest(".latepoint-booking-form-element").find(".step-confirmation-set-password").show(), e(this).closest(".info-box").hide(), !1
        })
    }

    function P() {
        if (e(".latepoint-login-form-w #facebook-signin-btn").length && e(".latepoint-login-form-w").length && e(".latepoint-login-form-w #facebook-signin-btn").on("click", function() {
                var t = e(this).closest(".latepoint-login-form-w");
                FB.login(function(a) {
                    if ("connected" === a.status && a.authResponse) {
                        var n = {
                                token: a.authResponse.accessToken
                            },
                            o = {
                                action: "latepoint_route_call",
                                route_name: t.find("#facebook-signin-btn").data("login-action"),
                                params: e.param(n),
                                layout: "none",
                                return_format: "json"
                            };
                        A(t), e.ajax({
                            type: "post",
                            dataType: "json",
                            url: latepoint_helper.ajaxurl,
                            data: o,
                            success: function e(a) {
                                "success" === a.status ? location.reload() : (latepoint_show_message_inside_element(a.message, t), O(!1, t))
                            }
                        })
                    }
                }, {
                    scope: "public_profile,email"
                })
            }), e(".latepoint-login-form-w #google-signin-btn").length && e(".latepoint-login-form-w").length) {
            var t = {},
                a;
            e(".latepoint-login-form-w").each(function() {
                var t = e(this);
                gapi.load("auth2", function() {
                    var a;
                    gapi.auth2.init({
                        client_id: t.find("meta[name=google-signin-client_id]").attr("content"),
                        cookiepolicy: "single_host_origin"
                    }).attachClickHandler(t.find("#google-signin-btn")[0], {}, function(a) {
                        var n = {
                                token: a.getAuthResponse().id_token
                            },
                            o = {
                                action: "latepoint_route_call",
                                route_name: t.find("#google-signin-btn").data("login-action"),
                                params: e.param(n),
                                layout: "none",
                                return_format: "json"
                            };
                        A(t), e.ajax({
                            type: "post",
                            dataType: "json",
                            url: latepoint_helper.ajaxurl,
                            data: o,
                            success: function e(a) {
                                "success" === a.status ? location.reload() : (latepoint_show_message_inside_element(a.message, t), O(!1, t))
                            }
                        })
                    }, function(e) {})
                })
            })
        }
    }

    function F() {
        E(), I(), latepoint_init_form_masks(), e(".step-contact-w").each(function() {
            var t = e(this).find('input[name="customer[first_name]"]').val() + " " + e(this).find('input[name="customer[last_name]"]').val();
            t = t.trim(), latepoint_update_summary_field(e(this).closest(".latepoint-booking-form-element"), "customer", t)
        }), e(".step-contact-w").on("keyup", 'input[name="customer[first_name]"], input[name="customer[last_name]"]', function() {
            var t = e(this).closest(".latepoint-booking-form-element");
            latepoint_update_summary_field(t, "customer", t.find('input[name="customer[first_name]"]').val() + " " + t.find('input[name="customer[last_name]"]').val())
        }), e(".step-contact-w").on("keyup", ".os-form-control.required", function() {
            var t;
            R(e(this).closest(".latepoint-booking-form-element").find(".step-contact-w .os-form-control.required"))
        }), e(".step-customer-logout-btn").on("click", function() {
            var t = e(this).closest(".latepoint-booking-form-element"),
                a = {
                    action: "latepoint_route_call",
                    route_name: e(this).data("btn-action"),
                    layout: "none",
                    return_format: "json"
                };
            return A(t), e.ajax({
                type: "post",
                dataType: "json",
                url: latepoint_helper.ajaxurl,
                data: a,
                success: function e(a) {
                    latepoint_reload_step(t)
                }
            }), !1
        }), e(".step-login-existing-customer-btn").on("click", function() {
            var t = e(this).closest(".latepoint-booking-form-element"),
                a = {
                    email: t.find('.os-step-existing-customer-login-w input[name="customer_login[email]"]').val(),
                    password: t.find('.os-step-existing-customer-login-w input[name="customer_login[password]"]').val()
                },
                n = {
                    action: "latepoint_route_call",
                    route_name: e(this).data("btn-action"),
                    params: e.param(a),
                    layout: "none",
                    return_format: "json"
                };
            return A(t), e.ajax({
                type: "post",
                dataType: "json",
                url: latepoint_helper.ajaxurl,
                data: n,
                success: function e(a) {
                    "success" === a.status ? latepoint_reload_step(t) : (latepoint_show_message_inside_element(a.message, t.find(".os-step-existing-customer-login-w")), O(!1, t))
                }
            }), !1
        })
    }

    function A(e) {
        e.removeClass("step-content-loaded").addClass("step-content-loading")
    }

    function O(e, t) {
        e && t.find(".latepoint-body .latepoint-step-content").replaceWith(e), t.removeClass("step-content-loading").addClass("step-content-mid-loading"), setTimeout(function() {
            t.removeClass("step-content-mid-loading").addClass("step-content-loaded")
        }, 50)
    }

    function E() {
        e("#facebook-signin-btn").length && e(".latepoint-booking-form-element").length && e("#facebook-signin-btn").on("click", function() {
            var t = e(this).closest(".latepoint-booking-form-element");
            FB.login(function(a) {
                if ("connected" === a.status && a.authResponse) {
                    var n = {
                            token: a.authResponse.accessToken
                        },
                        o = {
                            action: "latepoint_route_call",
                            route_name: t.find("#facebook-signin-btn").data("login-action"),
                            params: e.param(n),
                            layout: "none",
                            return_format: "json"
                        };
                    A(t), e.ajax({
                        type: "post",
                        dataType: "json",
                        url: latepoint_helper.ajaxurl,
                        data: o,
                        success: function e(a) {
                            "success" === a.status ? latepoint_reload_step(t) : (latepoint_show_message_inside_element(a.message, t.find(".os-step-existing-customer-login-w ")), O(!1, t))
                        }
                    })
                }
            }, {
                scope: "public_profile,email"
            })
        })
    }

    function I() {
        var t;
        e("#google-signin-btn").length && e(".latepoint-booking-form-element").length && e(".latepoint-booking-form-element").each(function() {
            var t = e(this);
            gapi.load("auth2", function() {
                var a;
                gapi.auth2.init({
                    client_id: t.find("meta[name=google-signin-client_id]").attr("content"),
                    cookiepolicy: "single_host_origin"
                }).attachClickHandler(t.find("#google-signin-btn")[0], {}, function(a) {
                    var n = {
                            token: a.getAuthResponse().id_token
                        },
                        o = {
                            action: "latepoint_route_call",
                            route_name: t.find("#google-signin-btn").data("login-action"),
                            params: e.param(n),
                            layout: "none",
                            return_format: "json"
                        };
                    A(t), e.ajax({
                        type: "post",
                        dataType: "json",
                        url: latepoint_helper.ajaxurl,
                        data: o,
                        success: function e(a) {
                            "success" === a.status ? latepoint_reload_step(t) : (latepoint_show_message_inside_element(a.message, t.find(".os-step-existing-customer-login-w ")), O(!1, t))
                        }
                    })
                }, function(e) {})
            })
        })
    }

    function S(e, t) {
        e.removeClass("step-changed").addClass("step-changing"), setTimeout(function() {
            var a = e.find('.latepoint-progress li[data-step-name="' + t + '"]');
            a.addClass("active").addClass("complete").prevAll().addClass("complete").removeClass("active"), a.nextAll().removeClass("complete").removeClass("active");
            var n = e.find('.latepoint-step-desc-library[data-step-name="' + t + '"]').html();
            e.find(".latepoint-step-desc").html(n);
            var o = e.find('.os-heading-text-library[data-step-name="' + t + '"]').html();
            e.find(".os-heading-text").html(o), setTimeout(function() {
                e.removeClass("step-changing").addClass("step-changed")
            }, 50)
        }, 500)
    }

    function D(e, t) {
        var a = e.find('.latepoint-progress li[data-step-name="' + t + '"]');
        a.addClass("active").addClass("complete").prevAll().addClass("complete").removeClass("active"), a.nextAll().removeClass("complete").removeClass("active")
    }

    function B(e, t) {
        var a = e.find('.latepoint-progress li[data-step-name="' + t + '"]');
        a.addClass("active").addClass("complete").prevAll().addClass("complete").removeClass("active"), a.nextAll().removeClass("complete").removeClass("active")
    }

    function N(e, t) {
        e.removeClass("step-changed").addClass("step-changing"), setTimeout(function() {
            e.find(".latepoint-step-desc").html(e.find(".latepoint-step-desc-library.active").removeClass("active").next(".latepoint-step-desc-library").addClass("active").html()), e.find(".os-heading-text").html(e.find(".os-heading-text-library.active").removeClass("active").next(".os-heading-text-library").addClass("active").html()), setTimeout(function() {
                e.removeClass("step-changing").addClass("step-changed")
            }, 50)
        }, 500)
    }

    function H(e, t) {
        e.removeClass("step-changed").addClass("step-changing"), setTimeout(function() {
            e.find(".latepoint-step-desc").html(e.find(".latepoint-step-desc-library.active").removeClass("active").prev(".latepoint-step-desc-library").addClass("active").html()), e.find(".os-heading-text").html(e.find(".os-heading-text-library.active").removeClass("active").prev(".os-heading-text-library").addClass("active").html()), setTimeout(function() {
                e.removeClass("step-changing").addClass("step-changed")
            }, 50)
        }, 500)
    }

    function R(t) {
        var a = !0;
        return t.each(function(t) {
            if ("" == e(this).val()) return a = !1, !1
        }), a
    }

    function M() {
        if (e(".latepoint-lightbox-close").on("click", function() {
                return e("body").removeClass("latepoint-lightbox-active"), e(".latepoint-lightbox-w").remove(), !1
            }), e(".latepoint-customer-timezone-selector-w select").on("change", function(t) {
                var a = e(this);
                a.closest(".latepoint-customer-timezone-selector-w").addClass("os-loading");
                var n = {
                    action: "latepoint_route_call",
                    route_name: e(this).closest(".latepoint-customer-timezone-selector-w").data("route-name"),
                    params: {
                        timezone_name: e(this).val()
                    },
                    layout: "none",
                    return_format: "json"
                };
                e.ajax({
                    type: "post",
                    dataType: "json",
                    url: latepoint_helper.ajaxurl,
                    data: n,
                    success: function e(t) {
                        a.closest(".latepoint-customer-timezone-selector-w").removeClass("os-loading"), "success" === t.status && location.reload()
                    }
                })
            }), e(".latepoint-timezone-selector-w select").on("change", function(t) {
                var a = e(this);
                a.closest(".latepoint-timezone-selector-w").addClass("os-loading");
                var n = {
                        action: "latepoint_route_call",
                        route_name: e(this).closest(".latepoint-timezone-selector-w").data("route-name"),
                        params: {
                            timezone_name: e(this).val()
                        },
                        layout: "none",
                        return_format: "json"
                    },
                    o = a.closest(".latepoint-booking-form-element");
                o.removeClass("step-content-loaded").addClass("step-content-loading"), e.ajax({
                    type: "post",
                    dataType: "json",
                    url: latepoint_helper.ajaxurl,
                    data: n,
                    success: function e(t) {
                        a.closest(".latepoint-timezone-selector-w").removeClass("os-loading"), o.removeClass("step-content-loading"), "success" === t.status && a.closest(".latepoint-booking-form-element").hasClass("current-step-datepicker") && latepoint_reload_step(a.closest(".latepoint-booking-form-element"))
                    }
                })
            }), !latepoint_helper.is_timezone_selected) {
            var t = Intl.DateTimeFormat().resolvedOptions().timeZone;
            t && t != e(".latepoint-timezone-selector-w select").val() && e(".latepoint-timezone-selector-w select").val(t).change()
        }
        e(".latepoint-booking-form-element .latepoint-form").on("submit", function(t) {
            var a = e(this),
                n = e(this).closest(".latepoint-booking-form-element");
            t.preventDefault();
            var c = a.serialize(),
                d = {
                    action: "latepoint_route_call",
                    route_name: a.data("route-name"),
                    params: c,
                    layout: "none",
                    return_format: "json"
                };
            n.removeClass("step-content-loaded").addClass("step-content-loading"), e.ajax({
                type: "post",
                dataType: "json",
                url: latepoint_helper.ajaxurl,
                data: d,
                success: function e(t) {
                    "success" === t.status ? (n.find(".latepoint_current_step").val(t.step_name), n.removeClass(function(e, t) {
                        return (t.match(/(^|\s)current-step-\S+/g) || []).join(" ")
                    }).addClass("current-step-" + t.step_name), setTimeout(function() {
                        n.removeClass("step-content-loading").addClass("step-content-mid-loading"), n.find(".latepoint-body").html(t.message), o(t.step_name, n), setTimeout(function() {
                            n.removeClass("step-content-mid-loading").addClass("step-content-loaded"), n.find(".latepoint-next-btn, .latepoint-prev-btn").removeClass("os-loading")
                        }, 50)
                    }, 500), t.is_pre_last_step ? n.find(".latepoint-next-btn span").text(n.find(".latepoint-next-btn").data("pre-last-step-label")) : n.find(".latepoint-next-btn span").text(n.find(".latepoint-next-btn").data("label")), t.is_last_step ? (n.addClass("hidden-buttons").find(".latepoint-footer").remove(), n.find(".latepoint-progress").css("opacity", 0), n.closest(".latepoint-summary-is-open").removeClass("latepoint-summary-is-open"), n.addClass("is-final-step")) : (!0 === t.show_next_btn ? s(n) : i(n), !0 === t.show_prev_btn ? l(n) : r(n)), S(n, t.step_name)) : (n.removeClass("step-content-loading").addClass("step-content-loaded"), n.find(".latepoint-next-btn, .latepoint-prev-btn").removeClass("os-loading"), latepoint_show_message_inside_element(t.message, n.find(".latepoint-body"))), a.find(".latepoint_step_direction").val("next")
                }
            })
        }), e(".latepoint-booking-form-element").on("click", ".lp-option", function() {
            e(this).closest(".lp-options").find(".lp-option.selected").removeClass("selected"), e(this).addClass("selected")
        }), e(".latepoint-booking-form-element .latepoint-next-btn").on("click", function(t) {
            if (e(this).hasClass("disabled") || e(this).hasClass("os-loading")) return !1;
            var n = e(this).closest(".latepoint-form"),
                o = n.closest(".latepoint-booking-form-element");
            return n.find(".latepoint_step_direction").val("next"), "payment" != o.find(".latepoint_current_step").val() || "card" != o.find('input[name="booking[payment_method]"]').val() || latepoint_helper.demo_mode ? n.submit() : (latepoint_helper.is_stripe_active && a(o), latepoint_helper.is_braintree_active && OsPaymentsBraintree.create_token(o)), e(this).addClass("os-loading"), !1
        }), e(".latepoint-booking-form-element .latepoint-prev-btn").on("click", function(t) {
            if (e(this).hasClass("disabled") || e(this).hasClass("os-loading")) return !1;
            var a = e(this).closest(".latepoint-form"),
                n = a.closest(".latepoint-booking-form-element"),
                o = a.find(".latepoint_current_step").val();
            if ("payment" == o) {
                var s = n.find(".step-payment-w");
                if (s.length) {
                    s.find(".lp-option.selected").removeClass("selected");
                    var l = s.data("current-payment-step"),
                        r = s.data("prev-payment-step");
                    if (r) {
                        var c = latepoint_get_new_prev_payment_class(s, r);
                        return s.removeClass(l).addClass(r).data("current-payment-step", r).data("prev-payment-step", c), i(n), !1
                    }
                }
            }
            if ("services" == o) {
                var d = n.find(".step-services-w");
                if (d.hasClass("selecting-service-duration")) return d.removeClass("selecting-service-duration"), d.find(".os-services > .os-item.selected").removeClass("selected"), !1;
                if (d.hasClass("selecting-service-category")) return d.find(".os-service-category-w .os-service-category-w.selected").length ? d.find(".os-service-category-w .os-service-category-w.selected").parents(".os-service-category-w").addClass("selected").find(".os-service-category-w.selected").removeClass("selected") : (d.removeClass("selecting-service-category").find(".os-service-category-w.selected").removeClass("selected"), d.removeClass("selecting-service-category").find(".os-service-categories-holder.show-selected-only").removeClass("show-selected-only")), !1
            }
            return latepoint_clear_step_vars(a.find(".latepoint_current_step").val(), a), a.find(".latepoint_step_direction").val("prev"), a.submit(), a.closest(".latepoint-with-summary").addClass("latepoint-summary-is-open"), e(this).addClass("os-loading"), !1
        })
    }
    var q, L, W, G, U;
    e(function() {
        P(), e(".latepoint-booking-form-element").length && (M(), e(".latepoint-booking-form-element").each(function() {
            o(e(this).find(".latepoint_current_step").val())
        })), e(".latepoint-request-booking-cancellation").on("click", function() {
            if (!confirm(latepoint_helper.cancel_booking_prompt)) return !1;
            var t = e(this),
                a = t.closest(".customer-booking"),
                n, o, s = {
                    action: "latepoint_route_call",
                    route_name: e(this).data("route"),
                    params: {
                        id: a.data("id")
                    },
                    layout: "none",
                    return_format: "json"
                };
            return t.addClass("os-loading"), e.ajax({
                type: "post",
                dataType: "json",
                url: latepoint_helper.ajaxurl,
                data: s,
                success: function e(a) {
                    "success" === a.status ? location.reload() : t.removeClass("os-loading")
                }
            }), !1
        }), e("body").on("click", ".os-step-tabs .os-step-tab", function() {
            e(this).closest(".os-step-tabs").find(".os-step-tab").removeClass("active"), e(this).addClass("active");
            var t = e(this).data("target");
            e(this).closest(".os-step-tabs-w").find(".os-step-tab-content").hide(), e(t).show()
        }), e("body").on("keyup", ".os-form-group .os-form-control", function() {
            e(this).val() ? e(this).closest(".os-form-group").addClass("has-value") : e(this).closest(".os-form-group").removeClass("has-value")
        }), e(".latepoint-tab-triggers").on("click", ".latepoint-tab-trigger", function() {
            var t = e(this).closest(".latepoint-tabs-w");
            return t.find(".latepoint-tab-trigger.active").removeClass("active"), t.find(".latepoint-tab-content").removeClass("active"), e(this).addClass("active"), t.find(".latepoint-tab-content" + e(this).data("tab-target")).addClass("active"), !1
        }), e(".latepoint-book-button, .os_trigger_booking").on("click", function() {
            var t = e(this),
                a = latepoint_helper.booking_button_route,
                n = {},
                s = {};
            t.data("show-service-categories") && (s.show_service_categories = t.data("show-service-categories")), t.data("show-locations") && (s.show_locations = t.data("show-locations")), t.data("show-services") && (s.show_services = t.data("show-services")), t.data("show-agents") && (s.show_agents = t.data("show-agents")), t.data("selected-location") && (s.selected_location = t.data("selected-location")), t.data("selected-agent") && (s.selected_agent = t.data("selected-agent")), t.data("selected-service") && (s.selected_service = t.data("selected-service")), t.data("selected-service-category") && (s.selected_service_category = t.data("selected-service-category")), t.data("calendar-start-date") && (s.calendar_start_date = t.data("calendar-start-date")), 0 == e.isEmptyObject(s) && (n.restrictions = s);
            var i = {
                action: "latepoint_route_call",
                route_name: a,
                params: n,
                layout: "none",
                return_format: "json"
            };
            return t.addClass("os-loading"), e.ajax({
                type: "post",
                dataType: "json",
                url: latepoint_helper.ajaxurl,
                data: i,
                success: function a(n) {
                    if ("success" === n.status) {
                        var s = "latepoint-lightbox-v2";
                        "yes" != t.data("hide-summary") && (s += " latepoint-with-summary"), latepoint_show_data_in_lightbox(n.message, s), M(), o(n.step), e("body").addClass("latepoint-lightbox-active"), t.removeClass("os-loading")
                    } else t.removeClass("os-loading")
                }
            }), !1
        })
    })
}(jQuery);
//# sourceMappingURL=main_front.js.map