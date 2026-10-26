import hexToRGBA from './hex-to-rgba';
// export const cssBoxShadow = (v) => {

//     if(v.openShadow === true){
//         return (v.inset || '') + ' ' + v.horizontal + 'px ' + v.vertical + 'px ' + v.blur + 'px ' + v.spread + 'px ' + v.color + ';'
//     }  else {
//       return
//    }
// }

// CSS Box Shadow
export const cssBoxShadow = (v) => {
    if(v.openShadow === true){
        return (v.inset || '') + ' ' + v.horizontal + 'px ' + v.vertical + 'px ' + v.blur + 'px ' + v.spread + 'px ' + v.color + ';'
    } else{
        return 
    }    
}

export const cssTextShadow = (v) => {
  if(v.openShadow === true){
    return v.horizontal + 'px ' + v.vertical + 'px ' + v.blur + 'px ' + v.color + ';'
   } else {
    return
   } 
}

// CSS Gradient
export const cssGradient = (v, type) => {
    let gradietValue = v.type == 'linear' ? 'linear-gradient(' + v.direction + 'deg, ' : 'radial-gradient( circle at ' + v.radial + ' , '
    gradietValue += v.color1 + ' ' + v.start + '%,' + v.color2 + ' ' + v.stop + '%)'
    if (type == 'object') {
        return { background: gradietValue }
    } else {
        gradietValue = 'background:' + gradietValue + (v.clip ? '-webkit-background-clip: text; -webkit-text-fill-color: transparent;' : '')
        // return '{' + gradietValue + '}';
        return gradietValue;
    }
}

// CSS Gradient
export const cssWithoutSelector = (v, type) => {
    let gradietValue = v.type == 'linear' ? 'linear-gradient(' + v.direction + 'deg, ' : 'radial-gradient( circle at ' + v.radial + ' , '
    gradietValue += v.color1 + ' ' + v.start + '%,' + v.color2 + ' ' + v.stop + '%)'
    if (type == 'object') {
        return { background: gradietValue }
    } else {
        gradietValue = gradietValue + (v.clip ? '-webkit-background-clip: text; -webkit-text-fill-color: transparent;' : '')
        // return '{' + gradietValue + '}';
        return gradietValue; 
    }
}

// CSS Background
const split_bg = (type, image = {}, imgPosition, imgAttachment, imgRepeat, imgSize, DefaultColor, bgGradient) => {
    let bgData = '' + DefaultColor +';' ;
    if (type == 'image') {
        bgData += (image.hasOwnProperty('url') ? 'background-image:url(' + image.url + ');' : '') + (imgPosition ? 'background-position:' + imgPosition + ';' : '') + (imgAttachment ? 'background-attachment:' + imgAttachment + ';' : '') +
            (imgRepeat ? 'background-repeat:' + imgRepeat + ';' : '') + (imgSize ? 'background-size:' + imgSize + ';' : '')
    }
    else if (type == 'gradient') {
        if (bgGradient && bgGradient.type == 'linear') {
            bgData += 'background-image: linear-gradient(' + bgGradient.direction + 'deg, ' + bgGradient.color1 + ' ' + bgGradient.start + '%,' + bgGradient.color2 + ' ' + bgGradient.stop + '%);'
        } else {
            bgData += 'background-image: radial-gradient( circle at ' + bgGradient.radial + ' , ' + bgGradient.color1 + ' ' + bgGradient.start + '%,' + bgGradient.color2 + ' ' + bgGradient.stop + '%);'
        }
    }
    return bgData;
}
export const cssBackground = (v) => {
     let background 
     if(v.bgType == ''){
        v.bgDefaultColor = '';
     }
    if(v.bgType !== ''){
        
        background = split_bg(v.bgType, v.bgImage, v.bgimgPosition, v.bgimgAttachment, v.bgimgRepeat, v.bgimgSize, hexToRGBA(v.bgDefaultColor,v.bgDefaultColorOpacity), v.bgGradient)
        
        if (v.bgType == 'video') {
            if (v.bgVideoFallback) {
                if (v.bgVideoFallback.url) {
                    background += 'background-image: url(' + v.bgVideoFallback.url + '); background-position: center; background-repeat: no-repeat; background-size: cover;'
                }
            }
        }
        if (background != '{}') { return background }
        return {};
    } else {
        return ''
    }        
}