class ArrayUtil {

  static remove(array, element) {
    const index = array.indexOf(element);
    if (index !== -1) array.splice(index, 1);
  }

  static clear(array) {
    array.splice(0, array.length);
  }

  static shuffle(array) {
    array.sort(() => {return 0.5 - Math.random()});
  }

  static randomElement(array) {
    return array[MathUtil.randRange(0, array.length - 1)];
  }

  static uniqueArray(array) {
    return array.filter((el, i, arr) => {
      return arr.indexOf(el) === i;   // only return the first instance of an element
    });
  }

}
