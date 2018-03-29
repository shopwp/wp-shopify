// import { getTotalCountsFromSession } from '../ws';
// import { requestSuccess } from '__mock__/requests';

it('Generic Request Success', async () => {

  const requestSuccess = jest.fn( () => {

    return new Promise( (resolve, reject) => {

      resolve({
        success: true,
        data: {
          type: 'success',
          message: 'arbitrary message'
        }
      });

    });

  });

  await expect( requestSuccess() ).resolves.toHaveProperty('success', true);


});


it('Generic Request Failure', async () => {

  const requestSuccess = jest.fn( () => {

    return new Promise( (resolve, reject) => {

      resolve({
        success: false,
        data: {
          type: 'success',
          message: 'arbitrary message'
        }
      });

    });

  });

  await expect( requestSuccess() ).resolves.toHaveProperty('success', false);


});


// it('Fetches data', async () => {
//
//   await expect( fetchData() ).resolves.toHaveProperty('success', true);
//
//
// });
