export const dateFormat = (date) => {
    const dt = new Date(date);
    const y = dt.getFullYear();
    const m = (dt.getMonth() + 1).toString().padStart(2, '0');
    const d = dt.getDate().toString().padStart(2, '0');

    return `${y}/${m}/${d}`;
}
