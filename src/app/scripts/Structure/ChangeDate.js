/**
 * Created by sahaque on 4/18/2017.
 */

function ChangeDate(date, time) {
    "use strict";
    this.date = date;
    this.time = time;
    this.notes = [];
}

ChangeDate.inherits(EntityAbstract);

ChangeDate.method('parseTree', function(tree, ver) {
    "use strict";
    this.ver =ver;
    var i1=this.findTag(tree, Tags.CHANGEDATE);
    if (i1!==false) {
        var sub2 = tree[i1][1];
        var i2 = this.findTag(sub2, Tags.DATE);
        if (i2!==false) {
            this.date = this.parseText(sub2[i2], Tags.DATE);
            if (sub2[i2][1]) {
                var i3=this.findTag(sub2[i2][1], Tags.TIME);
                if (i3!==false) {
                    this.time
                        = this.parseText(sub2[i2][1][i3], Tags.TIME);
                }
            }
            var off = 0;
            i2 = this.findTag(sub2, Tags.NOTE, off);
            while (i2!==false) {
                var tmp = new Note();
                tmp.parseTree([sub2[i2]], ver);
                this.notes.push(tmp);
                off = i2 + 1;
                i2 = this.findTag(sub2, Tags.NOTE, off);
            }
        }
    }
});

ChangeDate.prototype.toString = function () {
    var str = '(Date->' + this.date
        + ' ' + this.time
        + ', Notes->(';

    for (var i=0; i<this.notes.length; i++) {
        str += "\n" + this.notes[i];
    }

    str += '))';
    return str;
};