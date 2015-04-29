function snn(radius, dstimg, imgdata) {

var srcimg = imgdata,
    w = srcimg.width,
    h = srcimg.height,
    r = parseInt(radius),
    div = 1.0/((2*r + 1)*(2*r + 1)),
    srcdata = srcimg.data,
    dstdata = dstimg.data,
    sumr, sumg, sumb,
    rc, gc, bc, r1, g1, b1, r2, g2, b2,
    pv, pu, xystep, uvstep, delta1, delta2,
    i, j;

for(var y=0; y<h; y++) {
    xystep = y*w;
    for(var x=0; x<w; x++) {
      i = (xystep + x) << 2;
      sumr = 0, sumg = 0, sumb = 0;
      rc = srcdata[i];
      gc = srcdata[i + 1];
      bc = srcdata[i + 2];
      for(var v=-r; v<=r; v++) {
        uvstep = w*v;
        for(var u=-r; u<=r; u++) {
          j = (uvstep + u) << 2;
          if(srcdata[i + j]) {
            r1 = srcdata[i + j];
            g1 = srcdata[i + j + 1];
            b1 = srcdata[i + j + 2];
          } else {
            r1 = srcdata[i];
            g1 = srcdata[i + 1];
            b1 = srcdata[i + 2];
          }
          if(srcdata[i - j]) {
            r2 = srcdata[i - j];
            g2 = srcdata[i - j + 1];
            b2 = srcdata[i - j + 2];
          } else {
            r2 = srcdata[i];
            g2 = srcdata[i + 1];
            b2 = srcdata[i + 2];
          }
          delta1 = Math.sqrt((rc - r1)*(rc - r1) +
                             (gc - g1)*(gc - g1) +
                             (bc - b1)*(bc - b1));
          delta2 = Math.sqrt((rc - r2)*(rc - r2) +
                             (gc - g2)*(gc - g2) +
                             (bc - b2)*(bc - b2));
          if(delta1 < delta2) {
            sumr += r1;
            sumg += g1;
            sumb += b1;
          } else {
            sumr += r2;
            sumg += g2;
            sumb += b2;
          }
        }
      }
      dstdata[i] = sumr*div;
      dstdata[i + 1] = sumg*div;
      dstdata[i + 2] = sumb*div;
      dstdata[i + 3] = 255;
    }
}
    //return dstimg;   
   
    //return c.toDataURL();
    self.postMessage({'dstimg': dstimg});
}

self.onmessage = function(e) {
  snn(e.data.radius, e.data.dstimg, e.data.imgdata);
}