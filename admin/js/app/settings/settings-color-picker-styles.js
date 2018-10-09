import reactCSS from 'reactcss';


function pickerStyles() {

  return reactCSS({

    default: {
      wrapper: {
        padding: "4px",
        background: "#fff",
        borderRadius: "1px",
        boxShadow: "0 0 0 1px rgba(0,0,0,.1)",
        display: "inline-block",
        cursor: "pointer"
      },
      popover: {
        position: "absolute",
        zIndex: "2"
      },
      cover: {
        position: "fixed",
        top: "0px",
        right: "0px",
        bottom: "0px",
        left: "0px"
      }
    }

  });

}

function swatchStyles(color) {

  return {
    backgroundColor: color,
    width: "40px",
    height: "20px"
  }

}

export {
  pickerStyles,
  swatchStyles
}
