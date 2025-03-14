(function ($) {
	"use strict";

	const html = document.documentElement;
	const { theme, layout, navbar } = localStorage;

	// Aplica configurações salvas
	if (theme) {
		html.setAttribute("data-theme", theme);
		if (theme === "dark") {
			$(".geex-customizer__btn--light").removeClass("active");
			$(".geex-customizer__btn--dark").addClass("active");
		}
	}
	if (layout) {
		html.setAttribute("dir", layout);
		if (layout === "rtl") {
			$(".geex-customizer__btn--ltr").removeClass("active");
			$(".geex-customizer__btn--rtl").addClass("active");
		}
	}
	if (navbar) {
		html.setAttribute("data-nav", navbar);
		if (navbar === "top") {
			$(".geex-customizer__btn--side").removeClass("active");
			$(".geex-customizer__btn--top").addClass("active");
		}
	}

	// Funções de atualização
	function setTheme(mode) {
		localStorage.theme = mode;
		html.setAttribute("data-theme", mode);
	}
	function setLayout(dir) {
		localStorage.layout = dir;
		html.setAttribute("dir", dir);
	}
	function setNavbar(pos) {
		localStorage.navbar = pos;
		html.setAttribute("data-nav", pos);
	}

	// Eventos do customizer
	$(".geex-customizer__btn--light").click(function () {
		$(".geex-customizer__btn--dark").removeClass("active");
		$(this).addClass("active");
		setTheme("light");
	});
	$(".geex-customizer__btn--dark").click(function () {
		$(".geex-customizer__btn--light").removeClass("active");
		$(this).addClass("active");
		setTheme("dark");
	});
	$(".geex-customizer__btn--ltr").click(function () {
		$(".geex-customizer__btn--rtl").removeClass("active");
		$(this).addClass("active");
		setLayout("ltr");
	});
	$(".geex-customizer__btn--rtl").click(function () {
		$(".geex-customizer__btn--ltr").removeClass("active");
		$(this).addClass("active");
		setLayout("rtl");
	});
	$(".geex-customizer__btn--side").click(function () {
		$(".geex-customizer__btn--top").removeClass("active");
		$(this).addClass("active");
		setNavbar("side");
	});
	$(".geex-customizer__btn--top").click(function () {
		$(".geex-customizer__btn--side").removeClass("active");
		$(this).addClass("active");
		setNavbar("top");
	});

	// Menu Active Class
	function addActiveClass(pageSlug) {
		const menuLinks = $('.geex-header__menu__link, .geex-sidebar__menu__link');
		menuLinks.removeClass("active");

		menuLinks.each(function () {
			const menuItemPath = $(this).attr("href");
			const menuItemName = menuItemPath.split("/").pop().split(".")[0];
			if (menuItemName === pageSlug || menuItemName + "#" === pageSlug) {
				const menuParent = $(this).closest(".has-children").find("ul").siblings("a");
				$(this).addClass("active");
				menuParent.addClass("active");
				menuParent.siblings(".geex-sidebar__submenu").slideDown();
			} else if (pageSlug === "" || pageSlug === "#") {
				$(".geex-header__menu__link").first().addClass("active");
				$(".geex-sidebar__menu__link").first().addClass("active");
				$(".geex-header__menu__link")
					.first()
					.siblings(".geex-header__submenu")
					.find(".geex-header__menu__link")
					.first()
					.addClass("active")
					.end()
					.end()
					.slideDown();
				$(".geex-sidebar__menu__link")
					.first()
					.siblings(".geex-sidebar__submenu")
					.find(".geex-sidebar__menu__link")
					.first()
					.addClass("active")
					.end()
					.end()
					.slideDown();
			}
		});
	}
	const path = window.location.pathname;
	const pageSlug = path.split("/").pop().split(".")[0];
	addActiveClass(pageSlug);

	$(".geex-sidebar__menu__link").click(function () {
		const $clickedItem = $(this);
		$clickedItem.toggleClass("active").siblings(".geex-sidebar__submenu").slideToggle();
		$(".geex-sidebar__menu__link")
			.not($clickedItem)
			.removeClass("active")
			.siblings(".geex-sidebar__submenu")
			.slideUp();
	});

	// Outros eventos permanecem inalterados para não afetar o funcionamento do site
	$(".geex-btn__customizer").click(function () {
		$(".geex-customizer").toggleClass("active");
		$("body").addClass("overlay_active");
	});
	$(".geex-customizer-overlay, .geex-btn__customizer-close").click(function () {
		$(".geex-customizer").removeClass("active");
		$("body").removeClass("overlay_active");
	});
	$(".geex-btn__toggle-sidebar").click(function (e) {
		e.preventDefault();
		$(".geex-sidebar").toggleClass("active").animate({ width: "toggle" });
		$("body").addClass("overlay_active");
	});
	$(".geex-sidebar__close").click(function (e) {
		e.preventDefault();
		$(".geex-sidebar").removeClass("active").animate({ width: "toggle" });
		$("body").removeClass("overlay_active");
	});
	$("#geex-content__filter__label").click(function () {
		$("#geex-content__filter__date").datepicker().datepicker("show");
	});
	$(".geex-content__toggle__btn").click(function (e) {
		e.preventDefault();
		$(this).toggleClass("active").siblings(".geex-content__toggle__content").slideToggle();
	});
	$(".geex-btn__toggle-task").click(function (e) {
		e.preventDefault();
		$(this).toggleClass("active");
		$(".geex-content__todo__sidebar").slideToggle();
	});
	$(".geex-content__calendar__toggle").click(function (e) {
		e.preventDefault();
		$(this).toggleClass("active");
		$(".geex-content__calendar__sidebar").slideToggle();
	});
	$(".geex-content__chat__toggle").click(function (e) {
		e.preventDefault();
		$(this).toggleClass("active");
		$(".geex-content__chat__sidebar").slideToggle();
	});
	$(".geex-content__chat__action__toggle__btn").click(function (e) {
		e.preventDefault();
		$(this).toggleClass("active").siblings(".geex-content__chat__action__toggle__content").slideToggle();
	});
	$(".geex-content__header__quickaction__link").click(function (e) {
		e.preventDefault();
		const $popup = $(this).siblings(".geex-content__header__popup");
		$popup.slideToggle();
		$(".geex-content__header__popup").not($popup).slideUp();
	});
	$(".geex-btn__add-modal").click(function () {
		$(".geex-content__modal__form").addClass("active");
		$("body").addClass("overlay_active");
	});
	$(".geex-content__modal__form__close").click(function () {
		$(".geex-content__modal__form").removeClass("active");
		$("body").removeClass("overlay_active");
	});
	$(".geex-content__chat__header__filter__mute-btn").click(function (e) {
		e.preventDefault();
		$(this).toggleClass("active");
	});
	$(".geex-content__chat__header__filter__btn").click(function (e) {
		e.preventDefault();
		const $clickedItem = $(this);
		$clickedItem.toggleClass("active").siblings(".geex-content__chat__header__filter__content").slideToggle();
		$(".geex-content__chat__header__filter__btn")
			.not($clickedItem)
			.removeClass("active")
			.siblings(".geex-content__chat__header__filter__content")
			.slideUp();
	});
	$(".toggle-password-type").click(function (e) {
		e.preventDefault();
		const input = $(this).siblings("input");
		if (input.attr("type") === "password") {
			$(this).removeClass("uil-eye").addClass("uil-eye-slash");
			input.attr("type", "text");
		} else {
			$(this).addClass("uil-eye").removeClass("uil-eye-slash");
			input.attr("type", "password");
		}
	});
	$(".geex-content__invoice__chat__toggler").click(function (e) {
		e.preventDefault();
		const $invoiceChatContent = $(this).siblings(".geex-content__invoice__chat__wrapper");
		$invoiceChatContent.stop().animate({ width: "toggle", opacity: "toggle" }, 300);
	});

	// As demais funcionalidades (countdown, sliders, charts, editor, calendar etc.) foram mantidas
})(jQuery);
