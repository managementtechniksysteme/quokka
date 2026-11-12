/**
 * Calendar-date and wall-clock-time helpers.
 *
 * Calendar dates are handled as plain "YYYY-MM-DD" strings and times as
 * "HH:MM" strings. Neither is ever turned into a timestamp, so neither can
 * shift by a day (or an hour) across a timezone or DST boundary. A JS Date
 * is only used briefly, at local noon, for calendar arithmetic.
 */

const pad = number => String(number).padStart(2, '0');

const ISO_DATE_PATTERN = /^(\d{4})-(\d{2})-(\d{2})$/;
const MINUTES_PER_DAY = 24 * 60;

/** Format a JS Date's local calendar day as "YYYY-MM-DD". */
export function toIsoDate(date) {
    return date.getFullYear() + '-' + pad(date.getMonth() + 1) + '-' + pad(date.getDate());
}

/** Today's local calendar day as "YYYY-MM-DD". */
export function todayIsoDate() {
    return toIsoDate(new Date());
}

/** Whether the value is an existing calendar day in "YYYY-MM-DD" form. */
export function isValidIsoDate(value) {
    let match = typeof value === 'string' ? value.match(ISO_DATE_PATTERN) : null;

    if(!match) {
        return false;
    }

    let date = new Date(Number(match[1]), Number(match[2]) - 1, Number(match[3]), 12);

    return toIsoDate(date) === value;
}

/** Shift a "YYYY-MM-DD" date by whole calendar days. */
export function addDaysToIsoDate(isoDate, days) {
    let [year, month, day] = isoDate.split('-').map(Number);

    // noon keeps the day stable even on 23h/25h DST days
    return toIsoDate(new Date(year, month - 1, day + days, 12));
}

/** "YYYY-MM-DD" → "DD.MM.YYYY", or "DD.MM." without the year. */
export function formatIsoDate(isoDate, withYear = true) {
    let [year, month, day] = isoDate.split('-');

    return day + '.' + month + '.' + (withYear ? year : '');
}

/** "HH:MM" → minutes since midnight. */
export function timeToMinutes(time) {
    let [hours, minutes] = time.split(':').map(Number);

    return hours * 60 + minutes;
}

/** Minutes since midnight → "HH:MM", wrapping around midnight in both directions. */
export function minutesToTime(minutes) {
    let wrapped = ((Math.round(minutes) % MINUTES_PER_DAY) + MINUTES_PER_DAY) % MINUTES_PER_DAY;

    return pad(Math.floor(wrapped / 60)) + ':' + pad(wrapped % 60);
}
