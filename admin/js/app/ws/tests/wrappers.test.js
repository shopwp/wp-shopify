// import { syncOff } from '../wrappers';
// import { requestSuccess } from '__mock__/requests';

it('Turns sync off', async () => {

  const syncOff = jest.fn( () => {

      return Promise.all([
        new Promise((resolve, reject) => { resolve(1); }),
        new Promise((resolve, reject) => { resolve(2); })
      ]);

  });


  await expect( syncOff() ).resolves.toBe('');


});


// it('Fetches data', async () => {
//
//   await expect( fetchData() ).resolves.toHaveProperty('success', true);
//
//
// });



// async function syncOff() {
//
//   return Promise.all([
//
//     // Empty and end the $_SESSION
//     await endProgress(), // wps_progress_bar_end
//
//     // Clears the LS cache and any Transients
//     await clearAllCache() // wps_clear_cache
//
//   ]);
//
// }
