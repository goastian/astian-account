import AirDatepicker from "air-datepicker";
import "air-datepicker/air-datepicker.css";
import en from "air-datepicker/locale/en";
import { createPopper } from "@popperjs/core";
import anime from "animejs";

new AirDatepicker("#birthday", {
    locale: en,
    dateFormat: "yyyy-MM-dd",
    position({ $datepicker, $target, $pointer, isViewChange, done }) {
        let popper = createPopper($target, $datepicker, {
            placement: "bottom",
            onFirstUpdate: (state) => {
                !isViewChange && anime.remove($datepicker);

                $datepicker.style.transformOrigin = "center top";

                !isViewChange &&
                    anime({
                        targets: $datepicker,
                        opacity: [0, 1],
                        rotateX: [-90, 0],
                        easing: "spring(1.3, 80, 5, 0)",
                    });
            },
            modifiers: [
                {
                    name: "offset",
                    options: {
                        offset: [0, 10],
                    },
                },
                {
                    name: "arrow",
                    options: {
                        element: $pointer,
                    },
                },
                {
                    name: "computeStyles",
                    options: {
                        gpuAcceleration: false,
                    },
                },
            ],
        });

        return () => {
            anime({
                targets: $datepicker,
                opacity: 0,
                rotateX: -90,
                duration: 300,
                easing: "easeOutCubic",
            }).finished.then(() => {
                popper.destroy();
                done();
            });
        };
    },
});
