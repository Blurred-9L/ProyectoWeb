function Schedule( day, start, duration ){
    this.day = day;
    this.start = start;
    this.duration = duration;
}

Schedule.prototype.equals = function( schedule ){
    return ( this.day == schedule.day );
}
