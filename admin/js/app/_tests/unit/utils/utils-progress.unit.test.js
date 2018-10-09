import {
  checkForNoticeTypeInNoticeList
} from '../../../utils/utils-progress';


it('Should check for notice type in notice list', () => {

  const notices = [
    {
      type: 'error',
      message: 'Error Message'
    },
    {
      type: 'warning',
      message: 'Warning Message'
    }
  ];

  const resultTrue = checkForNoticeTypeInNoticeList(notices, 'warning');
  const resultFalse = checkForNoticeTypeInNoticeList(notices, 'success');

  expect(resultTrue)
    .toBeBoolean()
    .toBe(true)

  expect(resultFalse)
    .toBeBoolean()
    .toBe(false);

});
