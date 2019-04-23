function Shape(x, y, w, h, FillColor, EmptyColor) {
    this.x = x;
    this.y = y;
    this.w = w;
    this.h = h;
    this.FillColor = FillColor;
    this.EmptyColor = EmptyColor;
}

// get canvas element.
var c = document.getElementById('carParkSpace');

// check if context exist
if (c.getContext) {
    let myRect = [];
    let x = 90, y = 20;
    let w = x + 30, h = y + 170;
    for (let row = 0; row < 2; row++) {
        for (let col = 0; col < 4; col++) {
            myRect.push(new Shape(x, y, w, h, "#800000", "#32CD32"));
            x += 130
        }
        x = 90; //init x
        y += 210; // change another row
    }
    let ctx = c.getContext('2d');
    let savedRect;
    for (let i = 0; i < myRect.length; i++) {
        savedRect = myRect[i];
        ctx.rect(savedRect.x, savedRect.y, savedRect.w, savedRect.h);
        ctx.stroke();
        ctx.fillStyle = savedRect.EmptyColor;
        ctx.fillRect(savedRect.x, savedRect.y, savedRect.w, savedRect.h);
    }

    let update_status = [];
<?php
        foreach ($rows as $row) {
        $i++;
            ?>update_status.push(<?php echo $row['update_status']; ?>);
    <?php }?>

    for (let j = 0; j < update_status.length; j++) {
        if (update_status[j] === 1) {
            savedRect = myRect[j];
            ctx.fillStyle = savedRect.FillColor;
            ctx.fillRect(savedRect.x, savedRect.y, savedRect.w, savedRect.h);
        }
    }
}