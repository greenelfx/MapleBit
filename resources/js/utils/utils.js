export function errorsToString(obj) {
    let errors = "";
    for (const [key, value] of Object.entries(obj)) {
        errors += value;
    }
    return errors;
}